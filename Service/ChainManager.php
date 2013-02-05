<?php
namespace Kitpages\ChainBundle\Service;

use Kitpages\ChainBundle\Service\CommandManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

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

        $chain = new $chainConfig['class']($chainSlug);

        $chain->setCommandManager($this->commandManager);

        $chain->setCommandConfigList($chainConfig['command_list']);

        return $chain;

    }

}
