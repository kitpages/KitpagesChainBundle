<?php
namespace Kitpages\ChainBundle\Service;

use Kitpages\ChainBundle\ChainException;

class CommandManager
{

    protected $commandList = null;

    public function __construct(
        $commandList,
        $container
    )
    {
        $this->commandList = $commandList;
        $this->container = $container;
    }

    public function getCommand($commandSlug, $commandConfig = null)
    {
        $command = null;
        if (isset($this->commandList[$commandSlug])) {
            $commandManagerConfig = $this->commandList[$commandSlug];
            $command = new $commandManagerConfig['class'];
            $command->setContainer($this->container);
            if (isset($commandManagerConfig['parameter_list'])) {
                $command->setParameterList($commandManagerConfig['parameter_list']);
            }
            return $command;
        }

        if (!isset($commandConfig['class'])) {
            throw new ChainException("unknown commandSlug and class undefined in config");
        }

        if (!class_exists($commandConfig['class'])) {
            throw new ChainException("class ".$commandConfig['class']." doesn't exists");
        }

        $command = new $commandConfig['class'];
        $command->setContainer($this->container);
        if (isset($commandConfig['parameter_list'])) {
            $command->setParameterList($commandConfig['parameter_list']);
        }

        return $command;
    }

}
