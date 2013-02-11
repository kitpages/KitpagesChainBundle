<?php
namespace Kitpages\ChainBundle\Model;

use Kitpages\ChainBundle\Model\ChainInterface;
use Kitpages\ChainBundle\ChainException;

class Chain implements ChainInterface
{

    protected $commandList = null;

    public function __construct(
        $chainSlug
    ) {
        $this->chainSlug = $chainSlug;
    }

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
        $this->commandManager->getLogger()->info('Execute Chain '.$this->chainSlug.' begin');
        foreach($this->commandList as $command) {
            $this->commandManager->getLogger()->info('Command '.$command->getSlug().' begin');
            $command->execute();
            $this->commandManager->getLogger()->info('Command '.$command->getSlug().' end');
        }
        $this->commandManager->getLogger()->info('Execute Chain '.$this->chainSlug.' end');
    }

}