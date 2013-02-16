<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Step\StepInterface;
use Kitpages\ChainBundle\Step\StepEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StepSample2 implements StepInterface
{

    public $parameterList = array('return' => "originalSample2");

    public function execute(StepEvent $event = null) {
        return $this->parameterList['return'];
    }

    public function setParameter($parameter, $value) {
        $this->parameterList[$parameter] = $value;
        return $this;
    }

    public function setContainer(ContainerInterface $container)
    {
    }

}
