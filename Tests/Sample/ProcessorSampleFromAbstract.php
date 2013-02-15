<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Processor\ProcessorAbstract;
use Kitpages\ChainBundle\Processor\ProcessorEvent;

class ProcessorSampleFromAbstract
    extends ProcessorAbstract
{
    public function execute(ProcessorEvent $event = null)
    {
        return "executed";
    }
}
