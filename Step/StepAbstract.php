<?php
namespace Kitpages\ChainBundle\Step;

use Kitpages\ChainBundle\Step\StepInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class StepAbstract implements StepInterface
{
    protected $parameterList = array();
    protected $serviceList = array();

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

    public function setService($key, $service)
    {
        $this->serviceList[$key] = $service;
        return $this;
    }

    public function getService($key)
    {
        if (!isset($this->serviceList[$key])) {
            return null;
        }
        return $this->serviceList[$key];
    }

}