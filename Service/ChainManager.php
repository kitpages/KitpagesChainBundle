<?php
namespace Kitpages\ChainBundle\Service;

use Kitpages\ChainBundle\Service\CommandManager;
use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Model\ChainInterface;

class ChainManager
{

    protected $commandManager = null;
    protected $chainConfigList = null;

    public function __construct(
        $chainConfigList,
        CommandManager $commandManager
    )
    {
        $this->chainConfigList = $chainConfigList;
        $this->commandManager = $commandManager;
    }

    public function getChain($chainSlug)
    {
        $chainConfig = $this->chainConfigList[$chainSlug];
        // normalize chainConfig
        if (!isset($chainConfig['command_list'])) {
            $chainConfig['command_list'] = array();
        }

        // instanciate chain instance
        $chainClass = '\\Kitpages\\ChainBundle\\Model\\Chain';
        if (isset($chainConfig['class'])) {
            if (! class_exists($chainConfig['class']) ) {
                throw new ChainException("Chain class ".$chainConfig['class']." doesn't exists");
            }
            $chainClass = $chainConfig['class'];
        }
        $chain = new $chainClass();
        if (! $chain instanceof ChainInterface) {
            throw new ChainException("Chain class $chainClass doesn't implements ChainInterface");
        }

        // fill chain with command list
        $commandList = $this->initCommandList($chainConfig['command_list']);
        $chain->setCommandList($commandList);
        return $chain;
    }

    public function initCommandList($commandConfigList)
    {
        $commandList = array();
        foreach($commandConfigList as $commandSlug => $commandConfig) {
            $command = $this->commandManager->getCommand($commandSlug, $commandConfig);
            $commandList[$commandSlug] =  $command;
        }
        return $commandList;
    }
}
