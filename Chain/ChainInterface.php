<?php
namespace Kitpages\ChainBundle\Chain;

use Kitpages\ChainBundle\Step\StepEvent;

interface ChainInterface
{
    public function execute(StepEvent $event = null);

    public function setStepList($stepList);

    public function getStepList();
}