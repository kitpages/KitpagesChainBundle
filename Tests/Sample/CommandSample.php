<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\CommandAbstract;

class CommandSample extends CommandAbstract
{

    public $parameterList = array('return' => true);

    public function execute() {
        return $this->parameterList['return'];
    }
}
