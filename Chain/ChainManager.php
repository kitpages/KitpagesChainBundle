<?php
namespace Kitpages\ChainBundle\Chain;

use Kitpages\ChainBundle\Step\StepManager;
use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Chain\ChainInterface;

class ChainManager
{

    protected $stepManager = null;
    protected $chainConfigList = null;

    public function __construct(
        $chainConfigList,
        StepManager $stepManager
    )
    {
        $this->chainConfigList = $chainConfigList;
        $this->stepManager = $stepManager;
    }

    public function getChain($chainSlug)
    {
        $chainConfig = $this->chainConfigList[$chainSlug];
        // normalize chainConfig
        if (!isset($chainConfig['step_list'])) {
            $chainConfig['step_list'] = array();
        }

        // instanciate chain instance
        $chainClass = '\Kitpages\ChainBundle\Chain\Chain';
        if (isset($chainConfig['class'])) {
            if (! class_exists($chainConfig['class']) ) {
                throw new ChainException("Chain class ".$chainConfig['class']." doesn't exists");
            }
            $chainClass = $chainConfig['class'];
        }
        $chain = new $chainClass();
        if (! $chain instanceof ChainInterface) {
            throw new ChainException("Chain class $chainClass doesn't implements ChainInterface");
        }

        // fill chain with step list
        $stepList = $this->initStepList($chainConfig['step_list']);
        $chain->setStepList($stepList);
        return $chain;
    }

    public function initStepList($stepConfigList)
    {
        $stepList = array();
        foreach($stepConfigList as $stepSlug => $stepConfig) {
            $step = $this->stepManager->getStep($stepSlug, $stepConfig);
            $stepList[$stepSlug] =  $step;
        }
        return $stepList;
    }
}
