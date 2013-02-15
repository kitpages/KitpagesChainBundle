<?php
namespace Kitpages\ChainBundle\Processor;

use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Processor\ProcessorInterface;
use Kitpages\ChainBundle\Proxy\ProxyGenerator;

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

        $processorFinalConfig = array();

        if (isset($this->processorList[$processorName])) {
            $processorFinalConfig = $this->processorList[$processorName];
        }
        $processorFinalConfig = $this->customMerge($processorFinalConfig, $processorConfig);

        // processor name is only defined in chain config
        if (!isset($processorFinalConfig['class'])) {
            throw new ChainException("unknown processorName and class undefined in config");
        }
        $className = $processorFinalConfig['class'];

        if (!class_exists($className)) {
            throw new ChainException("class ".$className." doesn't exists");
        }

        // generate processor
        $proxyGenerator = new ProxyGenerator();
        $processor = $proxyGenerator->generateProcessProxy($className);
        $processor->__chainProxySetEventDispatcher($this->eventDispatcher);

        if (! $processor instanceof ProcessorInterface) {
            throw new ChainException("Processor class ".$className." doesn't implements ProcessorInterface");
        }

        // inject DIC
        $processor->setContainer($this->container);

        // set parameters
        if (isset($processorFinalConfig['parameter_list']) && is_array($processorFinalConfig['parameter_list'])) {
            foreach($processorFinalConfig['parameter_list'] as $key => $val) {
                $processor->setParameter($key, $val);
            }
        }

        return $processor;
    }

    protected function customMerge($tab1, $tab2)
    {
        $res = $tab1;
        foreach ($tab2 as $key => $val) {
            if (isset($res[$key]) && is_array($res[$key]) && is_array($val)) {
                $res[$key] = $this->customMerge($res[$key], $val);
                continue;
            }
            $res[$key] = $val;
        }
        return $res;
    }

}
