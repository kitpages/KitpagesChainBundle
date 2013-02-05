<?php
namespace Kitpages\ChainBundle\Model;

use Kitpages\ChainBundle\Model\CommandInterface;

abstract class Command implements CommandInterface
{

    protected $container = null;

    public function setParameter($parameter, $value)
    {
        if (substr($value, 0, 1) == '@') {

        } else {
            $this->$parameter = $value;
        }
    }

    public function execute()
    {
    }

    public function setParameterList($parameterList)
    {
        foreach($parameterList as $parameterName => $value) {
            $this->setParameter($parameterName, $value);
        }
    }

    public function getSlug()
    {
        return get_class($this);
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }
}