<?php
namespace Kitpages\ChainBundle\Chain;

use Kitpages\ChainBundle\Chain\ChainInterface;
use Kitpages\ChainBundle\Processor\ProcessorEvent;

class Chain implements ChainInterface
{

    protected $processorList = null;

    public function setProcessorList($processorList)
    {
        $this->processorList = $processorList;
    }

    public function getProcessorList()
    {
        return $this->processorList;
    }

    public function execute(ProcessorEvent $event = null)
    {
        if ($event == null) {
            $event = new ProcessorEvent();
        }
        foreach($this->processorList as $processor) {
            $result = $processor->execute($event);
            if ($event->isPropagationStopped()) {
                break;
            }
        }
        return $result;
    }

}