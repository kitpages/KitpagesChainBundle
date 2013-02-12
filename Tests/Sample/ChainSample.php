<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\ChainInterface;
use Kitpages\ChainBundle\ChainException;

class ChainSample implements ChainInterface
{
    private $commandList = array();

    public function execute()
    {
        $res = null;
        foreach($this->commandList as $slug => $command) {
            $res = $command->execute();
        }
        return $res;
    }

    public function setCommandList($commandList) {
        $this->commandList = $commandList;
        return $this;
    }

    public function getCommandList()
    {
        return $this->commandList;
    }
}