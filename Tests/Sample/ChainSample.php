<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\ChainInterface;
use Kitpages\ChainBundle\ChainException;

class ChainSample implements ChainInterface
{
    private $commandList = array();

    public function execute()
    {
        $return[] = true;
        foreach($this->commandList as $command) {
            $return[] = $command->execute();
        }

        return (array_search(false, $return) == false)?true:false;
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