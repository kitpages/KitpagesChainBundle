<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Processor\ProcessorInterface;
use Kitpages\ChainBundle\Processor\ProcessorEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProcessorSample implements ProcessorInterface
{

    public $parameterList = array('return' => "original");

    public function execute(ProcessorEvent $event = null)
    {
        echo "ProcessorSample execute() => ret=".$this->parameterList['return']."\n";
        return $this->parameterList['return'];
    }

    public function setParameter($key, $value)
    {
        $this->parameterList[$key] = $value;
        return $this;
    }

    public function setContainer(ContainerInterface $container)
    {
        return;
    }
}
