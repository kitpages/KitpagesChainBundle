<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\CommandInterface;

class CommandSample2 implements CommandInterface
{

    public $parameterList = array('return' => "originalSample2");

    public function execute() {
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