<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\CommandAbstract;
use Kitpages\ChainBundle\Model\CommandInterface;

class CommandSample implements CommandInterface
{

    public $parameterList = array('return' => true);

    public function execute() {
        return $this->parameterList['return'];
    }

    public function setParameter($parameter, $value) {
        $this->parameterList[$parameter] = $value;
        return $this;
    }

    public function setParameterList($parameterList) {
        $this->parameterList = $parameterList;
        return $this;
    }

    public function setContainer($container) {
        return;
    }
}
