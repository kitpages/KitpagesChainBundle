<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\CommandInterface;

class CommandSample implements CommandInterface
{
    private $parameterList = array();

    public function setParameter($parameter, $value)
    {
        $this->parameterList["$parameter"] = $value;
    }

    public function setParameterList($parameterList)
    {
        $this->parameterList = $parameterList;
    }

    public function execute() {
        return true;
    }

    public function getSlug()
    {
        return "samplecommand";
    }

    public function setContainer($container)
    {

    }

    public function getContainer()
    {

    }
}
