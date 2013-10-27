<?php
namespace Kitpages\ChainBundle\Chain;

use Kitpages\ChainBundle\Step\StepManager;
use Kitpages\ChainBundle\ChainException;

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

    public function getChain($chainName)
    {
        $chainConfig = $this->getChainConfig($chainName);
        $chainClass = $chainConfig['class'];
        $chain = new $chainClass();
        // fill chain with step list
        $stepList = $this->initStepList($chainConfig['step_list']);
        $chain->setStepList($stepList);
        return $chain;
    }

    public function getChainConfig($chainName)
    {
        $chainConfig = $this->chainConfigList[$chainName];
        $chainConfig = $this->normalizeChainConfig($chainConfig);
        $chainClass = $chainConfig['class'];
        if (! class_exists($chainClass) ) {
            throw new ChainException("Chain class $chainClass doesn't exists");
        }
        $refClass = new \ReflectionClass($chainClass);
        if (!$refClass->implementsInterface('\Kitpages\ChainBundle\Chain\ChainInterface')) {
            throw new ChainException("Chain class $chainClass doesn't implements ChainInterface");
        }
        return $chainConfig;
    }

    public function normalizeChainConfig($chainConfig)
    {
        // normalize chainConfig
        if (!isset($chainConfig['step_list'])) {
            $chainConfig['step_list'] = array();
        }
        // default chain class
        if (!isset($chainConfig['class'])) {
            $chainConfig['class'] = '\Kitpages\ChainBundle\Chain\Chain';
        }
        // beginning backslash
        $chainConfig['class'] = '\\'.ltrim($chainConfig['class'], '\\');

        // defines default values for help
        if (!isset($chainConfig["help"])) {
            $chainConfig["help"] = array();
        }
        if (!isset($chainConfig["help"]["short"])) {
            $chainConfig["help"]["short"] = "no help";
        }
        if (!isset($chainConfig["help"]["complete"])) {
            $chainConfig["help"]["complete"] = "no description";
        }
        if (!isset($chainConfig["help"]["private"])) {
            $chainConfig["help"]["private"] = false;
        }

        return $chainConfig;
    }

    public function initStepList($stepConfigList)
    {
        $stepList = array();
        foreach($stepConfigList as $stepName => $stepConfig) {
            $parentSharedStepName = null;
            if (isset($stepConfig["parent_shared_step"])) {
                $parentSharedStepName = $stepConfig["parent_shared_step"];
            }
            $step = $this->stepManager->getStep($parentSharedStepName, $stepConfig);
            $stepList[$stepName] =  $step;
        }
        return $stepList;
    }
}
