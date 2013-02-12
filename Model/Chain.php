<?php
namespace Kitpages\ChainBundle\Model;

use Kitpages\ChainBundle\Model\ChainInterface;
use Kitpages\ChainBundle\ChainException;

class Chain implements ChainInterface
{

    protected $commandList = null;

    public function setCommandList($commandList)
    {
        $this->commandList = $commandList;
    }

    public function getCommandList()
    {
        return $this->commandList;
    }

    public function execute()
    {
        $result = null;
        foreach($this->commandList as $command) {
            $result = $command->execute();
        }
        return $result;
    }

}