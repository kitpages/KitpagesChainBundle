<?php
namespace Kitpages\ChainBundle\Service;

use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Model\CommandInterface;

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

    public function getCommand($commandSlug, $commandConfig = array())
    {
        $command = null;

        // if command slug exists in command list config
        if (isset($this->commandList[$commandSlug])) {
            $commandManagerConfig = $this->commandList[$commandSlug];

            // instanciate class
            if (isset($commandManagerConfig['class'])) {
                $className = $commandManagerConfig['class'];
            }
            if (isset($commandConfig['class'])) {
                $className = $commandConfig['class'];
            }
            if (!class_exists($className)) {
                throw new ChainException("class $className doesn't exist");
            }
            $command = new $className();
            if (! $command instanceof CommandInterface) {
                throw new ChainException("Command class $className doesn't implements CommandInterface");
            }


            // inject DIC
            $command->setContainer($this->container);

            // inject parameters from command config
            if (isset($commandManagerConfig['parameter_list']) && is_array($commandManagerConfig['parameter_list'])) {
                foreach($commandManagerConfig['parameter_list'] as $key => $val) {
                    $command->setParameter($key, $val);
                }
            }

            // inject parameters from chain config or directly injected
            if (isset($commandConfig['parameter_list']) && is_array($commandConfig['parameter_list'])) {
                foreach($commandConfig['parameter_list'] as $key => $val) {
                    $command->setParameter($key, $val);
                }
            }
            return $command;
        }

        // command slug is only defined in chain config
        if (!isset($commandConfig['class'])) {
            throw new ChainException("unknown commandSlug and class undefined in config");
        }

        if (!class_exists($commandConfig['class'])) {
            throw new ChainException("class ".$commandConfig['class']." doesn't exists");
        }

        $command = new $commandConfig['class']();

        if (! $command instanceof CommandInterface) {
            throw new ChainException("Command class ".$commandConfig['class']." doesn't implements CommandInterface");
        }

        $command->setContainer($this->container);
        if (isset($commandConfig['parameter_list']) && is_array($commandConfig['parameter_list'])) {
            foreach($commandConfig['parameter_list'] as $key => $val) {
                $command->setParameter($key, $val);
            }
        }

        return $command;
    }

}
