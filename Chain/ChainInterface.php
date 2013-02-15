<?php
namespace Kitpages\ChainBundle\Chain;

use Kitpages\ChainBundle\Processor\ProcessorEvent;

interface ChainInterface
{
    public function execute(ProcessorEvent $event = null);

    public function setProcessorList($processorList);

    public function getProcessorList();
}