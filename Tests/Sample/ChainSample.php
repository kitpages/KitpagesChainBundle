<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Chain\ChainInterface;
use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Step\StepEvent;

class ChainSample implements ChainInterface
{
    private $stepList = array();

    public function execute(StepEvent $event = null)
    {
        $res = null;
        if ($event == null) {
            $event = new StepEvent();
        }
        foreach($this->stepList as $slug => $step) {
            $res = $step->execute($event);
        }
        //echo "ChainSample execute() => ret=$res\n";
        return $res;
    }

    public function setStepList($stepList) {
        $this->stepList = $stepList;
        return $this;
    }

    public function getStepList()
    {
        return $this->stepList;
    }
}