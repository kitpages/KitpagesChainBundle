<?php
namespace Kitpages\ChainBundle\Step;

use Kitpages\ChainBundle\Step\StepEvent;

interface StepInterface
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
     * @param StepEvent $event
     * @return StepEvent : $event transformed
     */
    public function execute(StepEvent $event = null);

    /**
     * used to receive services
     *
     * @param string $key
     * @param object $service : an object (hopefuly a service)
     * @return self instance for fluent interface
     */
    public function setService($key, $service);
}