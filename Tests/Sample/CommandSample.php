<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\CommandInterface;

class CommandSample implements CommandInterface
{

    public $parameterList = array('return' => "original");

    public function execute() {
        echo "CommandSample execute() => ret=".$this->parameterList['return']."\n";
        return $this->parameterList['return'];
    }

    public function setParameter($parameter, $value) {
        $this->parameterList[$parameter] = $value;
        return $this;
    }

    public function setContainer($container) {
        return;
    }
}
