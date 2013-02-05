<?php
namespace Kitpages\ChainBundle\Service;

class CommandManager
{

    protected $logger = null;
    protected $commandList = null;

    public function __construct(
        $commandList,
        $container
    )
    {
        $this->commandList = $commandList;
        $this->logger = $container->get('logger');
        $this->container = $container;
    }

    public function getCommand($commandSlug, $commandConfig = null)
    {
        $command = null;
        if (isset($this->commandList[$commandSlug])) {
            $this->logger->info('Command '.$commandSlug.' init');
            $commandManagerConfig = $this->commandList[$commandSlug];
            $command = new $commandManagerConfig['class'];
            $command->setContainer($this->container);
            if (isset($commandManagerConfig['parameter_list'])) {
                $command->setParameterList($commandManagerConfig['parameter_list']);
            }
        }
        //echo var_dump($this->container->get('doctrine'));
        if($commandConfig != null) {
            if ($command == null && isset($commandConfig['class'])) {
                $this->logger->info('Command '.$commandSlug.' init');
                $command = new $commandConfig['class'];
                $command->setContainer($this->container);
            }
            if (isset($commandConfig['parameter_list'])) {
                $command->setParameterList($commandConfig['parameter_list']);
            }
        }

        if($command == null) {
            $this->logger->info('Command '.$commandSlug.' no found in command_list general');
            return null;
        } else {
            return $command;
        }
    }

}
