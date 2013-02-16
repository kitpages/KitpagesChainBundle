<?php
namespace Kitpages\ChainBundle\Chain;

use Kitpages\ChainBundle\Chain\ChainInterface;
use Kitpages\ChainBundle\Step\StepEvent;

class Chain implements ChainInterface
{

    protected $stepList = null;

    public function setStepList($stepList)
    {
        $this->stepList = $stepList;
    }

    public function getStepList()
    {
        return $this->stepList;
    }

    public function execute(StepEvent $event = null)
    {
        if ($event == null) {
            $event = new StepEvent();
        }
        foreach($this->stepList as $step) {
            $result = $step->execute($event);
            if ($event->isPropagationStopped()) {
                break;
            }
        }
        return $result;
    }

}