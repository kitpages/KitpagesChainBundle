<?php
namespace Kitpages\ChainBundle\Chain;

use Kitpages\ChainBundle\Processor\ProcessorManager;
use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Chain\ChainInterface;

class ChainManager
{

    protected $processorManager = null;
    protected $chainConfigList = null;

    public function __construct(
        $chainConfigList,
        ProcessorManager $processorManager
    )
    {
        $this->chainConfigList = $chainConfigList;
        $this->processorManager = $processorManager;
    }

    public function getChain($chainSlug)
    {
        $chainConfig = $this->chainConfigList[$chainSlug];
        // normalize chainConfig
        if (!isset($chainConfig['processor_list'])) {
            $chainConfig['processor_list'] = array();
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

        // fill chain with processor list
        $processorList = $this->initProcessorList($chainConfig['processor_list']);
        $chain->setProcessorList($processorList);
        return $chain;
    }

    public function initProcessorList($processorConfigList)
    {
        $processorList = array();
        foreach($processorConfigList as $processorSlug => $processorConfig) {
            $processor = $this->processorManager->getProcessor($processorSlug, $processorConfig);
            $processorList[$processorSlug] =  $processor;
        }
        return $processorList;
    }
}
