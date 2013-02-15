<?php
namespace Kitpages\ChainBundle\Chain;

use Kitpages\ChainBundle\Chain\ChainInterface;
use Kitpages\ChainBundle\ChainException;

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

    public function execute()
    {
        $result = null;
        foreach($this->processorList as $processor) {
            $result = $processor->execute();
        }
        return $result;
    }

}