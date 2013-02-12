<?php
namespace Kitpages\ChainBundle\Service;

use Kitpages\ChainBundle\Service\CommandManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Kitpages\ChainBundle\ChainException;

class ChainManager
{

    protected $logger = null;
    protected $commandManager = null;
    protected $chainConfigList = null;

    public function __construct(
        $chainConfigList,
        CommandManager $commandManager,
        LoggerInterface $logger
    )
    {
        $this->chainConfigList = $chainConfigList;
        $this->commandManager = $commandManager;
        $this->logger = $logger;
    }

    public function getChain($chainSlug)
    {
        $chainConfig = $this->chainConfigList[$chainSlug];

        // instanciate chain instance
        $chainClass = '\\Kitpages\\ChainBundle\\Model\\Chain';
        if (isset($chainConfig['class']) && class_exists($chainConfig['class'])) {
            if (! class_exists($chainConfig['class']) ) {
                throw new ChainException("Chain class ".$chainConfig['class']." doesn't exists");
            }
            $chainClass = $chainConfig['class'];
        }
        $chain = new $chainClass();

        // fill chain with command list
        $commandList = $this->initCommandList($chainConfig['command_list']);
        $chain->setCommandList($commandList);
        return $chain;
    }

    public function initCommandList($commandConfigList)
    {
        $commandList = array();
        foreach($commandConfigList as $commandSlug => $commandConfig) {
            try {
                $command = $this->commandManager->getCommand($commandSlug, $commandConfig);
                $commandList[$commandSlug] =  $command;
            } catch (ChainException $e) {
                throw new ChainException($e->getMessage());
            }
        }
        return $commandList;
    }
}
