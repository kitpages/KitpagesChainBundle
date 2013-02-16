<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Step\StepAbstract;
use Kitpages\ChainBundle\Step\StepEvent;

class StepSampleFromAbstract
    extends StepAbstract
{
    public function execute(StepEvent $event = null)
    {
        return "executed";
    }
}
