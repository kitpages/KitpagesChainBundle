<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Chain\ChainInterface;
use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Processor\ProcessorEvent;

class ChainSample implements ChainInterface
{
    private $processorList = array();

    public function execute(ProcessorEvent $event = null)
    {
        $res = null;
        if ($event == null) {
            $event = new ProcessorEvent();
        }
        foreach($this->processorList as $slug => $processor) {
            $res = $processor->execute($event);
        }
        //echo "ChainSample execute() => ret=$res\n";
        return $res;
    }

    public function setProcessorList($processorList) {
        $this->processorList = $processorList;
        return $this;
    }

    public function getProcessorList()
    {
        return $this->processorList;
    }
}