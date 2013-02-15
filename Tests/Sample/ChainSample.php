<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\ChainInterface;
use Kitpages\ChainBundle\ChainException;

class ChainSample implements ChainInterface
{
    private $processorList = array();

    public function execute()
    {
        $res = null;
        foreach($this->processorList as $slug => $processor) {
            $res = $processor->execute();
        }
        echo "ChainSample execute() => ret=$res\n";
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