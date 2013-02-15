<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\ProcessorAbstract;
use Kitpages\ChainBundle\Event\ProcessorEvent;

class ProcessorSampleFromAbstract
    extends ProcessorAbstract
{
    public function execute(ProcessorEvent $event = null)
    {
        return "executed";
    }
}
