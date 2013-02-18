<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Step\StepInterface;
use Kitpages\ChainBundle\Step\StepEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StepSample implements StepInterface
{

    public $parameterList = array('return' => "original");

    public function execute(StepEvent $event = null)
    {
        echo "StepSample execute() => ret=".$this->parameterList['return']."\n";
        if ($this->getParameter("throw_exception")) {
            throw new \Kitpages\ChainBundle\ChainException("unit test exception");
        }
        return $this->parameterList['return'];
    }

    public function setParameter($key, $value)
    {
        $this->parameterList[$key] = $value;
        return $this;
    }

    public function getParameter($key)
    {
        if (!isset($this->parameterList[$key])) {
            return null;
        }
        return $this->parameterList[$key];
    }

    public function setContainer(ContainerInterface $container)
    {
        return;
    }
}
