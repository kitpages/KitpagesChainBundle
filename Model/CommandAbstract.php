<?php
namespace Kitpages\ChainBundle\Model;

use Kitpages\ChainBundle\Model\CommandInterface;

abstract class CommandAbstract implements CommandInterface
{
    protected $container = null;

    public function setParameter($parameter, $value)
    {
        $this->parameterList[$parameter] = $value;
        return $this;
    }

    public function setContainer($container)
    {
        $this->container = $container;
    }

    protected function getContainer()
    {
        return $this->container;
    }
}