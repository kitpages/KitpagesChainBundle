<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Processor\ProcessorInterface;
use Kitpages\ChainBundle\Processor\ProcessorEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProcessorSample2 implements ProcessorInterface
{

    public $parameterList = array('return' => "originalSample2");

    public function execute(ProcessorEvent $event = null) {
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