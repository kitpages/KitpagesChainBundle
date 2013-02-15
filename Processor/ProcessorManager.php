<?php
namespace Kitpages\ChainBundle\Processor;

use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Processor\ProcessorInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProcessorManager
{

    protected $processorList = null;
    protected $container = null;
    protected $eventDispatcher = null;

    public function __construct(
        $processorList,
        ContainerInterface $container,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->processorList = $processorList;
        $this->container = $container;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getProcessor($processorName, $processorConfig = array())
    {
        $processor = null;

        // if processor name exists in processor list config
        if (isset($this->processorList[$processorName])) {
            $processorManagerConfig = $this->processorList[$processorName];

            // instanciate class
            if (isset($processorManagerConfig['class'])) {
                $className = $processorManagerConfig['class'];
            }
            if (isset($processorConfig['class'])) {
                $className = $processorConfig['class'];
            }
            if (!class_exists($className)) {
                throw new ChainException("class $className doesn't exist");
            }
            $processor = new $className();
            if (! $processor instanceof ProcessorInterface) {
                throw new ChainException("Processor class $className doesn't implements ProcessorInterface");
            }


            // inject DIC
            $processor->setContainer($this->container);

            // inject parameters from processor config
            if (isset($processorManagerConfig['parameter_list']) && is_array($processorManagerConfig['parameter_list'])) {
                foreach($processorManagerConfig['parameter_list'] as $key => $val) {
                    $processor->setParameter($key, $val);
                }
            }

            // inject parameters from chain config or directly injected
            if (isset($processorConfig['parameter_list']) && is_array($processorConfig['parameter_list'])) {
                foreach($processorConfig['parameter_list'] as $key => $val) {
                    $processor->setParameter($key, $val);
                }
            }
            return $processor;
        }

        // processor name is only defined in chain config
        if (!isset($processorConfig['class'])) {
            throw new ChainException("unknown processorName and class undefined in config");
        }

        if (!class_exists($processorConfig['class'])) {
            throw new ChainException("class ".$processorConfig['class']." doesn't exists");
        }

        $processor = new $processorConfig['class']();

        if (! $processor instanceof ProcessorInterface) {
            throw new ChainException("Processor class ".$processorConfig['class']." doesn't implements ProcessorInterface");
        }

        $processor->setContainer($this->container);
        if (isset($processorConfig['parameter_list']) && is_array($processorConfig['parameter_list'])) {
            foreach($processorConfig['parameter_list'] as $key => $val) {
                $processor->setParameter($key, $val);
            }
        }

        return $processor;
    }

}
