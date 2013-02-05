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

    public function isCommandList()
    {
        if ($this->commandList == null) {
            return false;
        }
        return true;
    }

    public function initCommandList()
    {
        if (!$this->isCommandList()) {
            foreach($this->commandConfigList as $commandSlug => $commandConfig) {
                try {
                    $command = $this->commandManager->getCommand($commandSlug, $commandConfig);
                    if ($command == null) {
                        $this->logger->err('Command '.$commandSlug.' did not class.');
                        throw new ChainException("The $commandSlug command did not class.");
                    }
                    $this->commandList[] =  $command;
                } catch (ChainException $exc) {
                    throw new ChainException($exc->getMessage());
                }
            }
        }
    }

    public function getCommandList()
    {
        $this->initCommandList();
        return $this->commandList;
    }

    public function execute()
    {
        $this->logger->info('Chain '.$this->chainSlug.' begin');
        $this->initCommandList();
        foreach($this->commandList as $command) {
            $this->logger->info('Command '.$command->getSlug().' begin');
            $command->execute();
            $this->logger->info('Command '.$command->getSlug().' end');
        }
        $this->logger->info('Chain '.$this->chainSlug.' end');
    }


    function setCommandConfigList($commandConfigList)
    {
        $this->commandConfigList = $commandConfigList;
    }

    function setCommandManager($commandManager)
    {
        $this->commandManager = $commandManager;
    }
}