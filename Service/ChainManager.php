<?php
namespace Kitpages\ChainBundle\Service;

use Kitpages\ChainBundle\Service\CommandManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Kitpages\ChainBundle\ChainException;

class ChainManager
{

    protected $logger = null;
    protected $commandManager = null;
    protected $chainList = null;

    public function __construct(
        $chainList,
        CommandManager $commandManager,
        LoggerInterface $logger
    )
    {
        $this->chainList = $chainList;
        $this->commandManager = $commandManager;
        $this->logger = $logger;
    }

    public function getChain($chainSlug)
    {

        $chainConfig = $this->chainList[$chainSlug];

        $chain = new $chainConfig['class']();

        $this->logger->info('Create Chain '.$chainSlug.' begin');

        $commandList = $this->initCommandList($chainConfig['command_list']);

        $chain->setCommandList($commandList);

        $this->logger->info('Create Chain '.$chainSlug.' end');

        return $chain;

    }

    public function initCommandList($commandConfigList)
    {
        $commandList = array();
        foreach($commandConfigList as $commandSlug => $commandConfig) {
            try {
                $command = $this->commandManager->getCommand($commandSlug, $commandConfig);
                if ($command == null) {
                    $this->logger->err('Command '.$commandSlug.' did not class.');
                    throw new ChainException("The $commandSlug command did not class.");
                }
                $commandList[$commandSlug] =  $command;
            } catch (ChainException $exc) {
                throw new ChainException($exc->getMessage());
            }
        }
        return $commandList;
    }


}
