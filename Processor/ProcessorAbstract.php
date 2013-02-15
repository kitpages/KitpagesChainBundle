<?php
namespace Kitpages\ChainBundle\Processor;

use Kitpages\ChainBundle\Processor\ProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ProcessorAbstract implements ProcessorInterface
{
    protected $container = null;
    protected $parameterList = array();

    public function setParameter($key, $value)
    {
        $this->parameterList[$key] = $value;
        return $this;
    }

    public function getParameter($key)
    {
        if (!array_key_exists($key, $this->parameterList)) {
            return null;
        }
        return $this->parameterList[$key];
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }
}