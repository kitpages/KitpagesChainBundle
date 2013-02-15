<?php
namespace Kitpages\ChainBundle\Processor;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Kitpages\ChainBundle\Processor\ProcessorEvent;

interface ProcessorInterface
{
    /**
     * Used to receive parameters from the configuration
     *
     * @param $key
     * @param $value
     * @return self instance for fluent interface
     */
    public function setParameter($key, $value);

    /**
     * method that executes the processing
     *
     * @param ProcessorEvent $event
     * @return ProcessorEvent : $event transformed
     */
    public function execute(ProcessorEvent $event = null);

    /**
     * used to receive the DIC
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @return self instance for fluent interface
     */
    public function setContainer(ContainerInterface $container);
}