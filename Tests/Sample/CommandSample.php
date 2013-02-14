<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\CommandInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommandSample implements CommandInterface
{

    public $parameterList = array('return' => "original");

    public function execute()
    {
        echo "CommandSample execute() => ret=".$this->parameterList['return']."\n";
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
