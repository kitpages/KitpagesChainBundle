<?php
namespace Kitpages\ChainBundle\Step;

use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Step\StepInterface;
use Kitpages\ChainBundle\Proxy\ProxyGenerator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StepManager
{

    protected $stepList = null;
    protected $container = null;
    protected $eventDispatcher = null;

    public function __construct(
        $stepList,
        ContainerInterface $container,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->stepList = $stepList;
        $this->container = $container;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getStep($stepName, $stepConfig = array())
    {
        $step = null;

        $stepFinalConfig = $this->getResultingConfig($stepName);

        $stepFinalConfig = $this->customMerge($stepFinalConfig, $stepConfig);

        // step name is only defined in chain config
        if (!isset($stepFinalConfig['class'])) {
            throw new ChainException("unknown stepName and class undefined in config");
        }
        $className = $stepFinalConfig['class'];

        if (!class_exists($className)) {
            throw new ChainException("class ".$className." doesn't exists");
        }

        // generate step
        $proxyGenerator = new ProxyGenerator();
        $step = $proxyGenerator->generateProcessProxy($className);
        $step->__chainProxySetEventDispatcher($this->eventDispatcher);

        if (! $step instanceof StepInterface) {
            throw new ChainException("Step class ".$className." doesn't implements StepInterface");
        }

        // inject Services
        if (isset($stepFinalConfig['service_list']) && is_array($stepFinalConfig['service_list'])) {
            foreach($stepFinalConfig['service_list'] as $key => $serviceName) {
                $service = $this->container->get($serviceName);
                $step->setService($key, $service);
            }
        }

        // set parameters
        if (isset($stepFinalConfig['parameter_list']) && is_array($stepFinalConfig['parameter_list'])) {
            foreach($stepFinalConfig['parameter_list'] as $key => $val) {
                $step->setParameter($key, $val);
            }
        }

        return $step;
    }

    public function getResultingConfig($stepName)
    {
        $stepConfigStack = array();
        $runningStepName = $stepName;
        // register list of steps inherited
        while (isset($this->stepList[$runningStepName])) {
            array_push($stepConfigStack, $stepConfig = $this->stepList[$runningStepName]);
            $runningStepName = null;
            if (isset($stepConfig["parent_shared_step"])) {
                $runningStepName = $stepConfig["parent_shared_step"];
            }
        }
        // build final stepConfig by merging steps in the right order
        $stepFinalConfig = array();
        while($stepConfig = array_pop($stepConfigStack)) {
            $stepFinalConfig = $this->customMerge($stepFinalConfig, $stepConfig);
        }

        // erase help and put only last level help if present
        // because help is never inherited
        if (isset($this->stepList[$stepName])) {
            $help = array();
            $originalStepConfig = $this->stepList[$stepName];
            if (isset($originalStepConfig['help'])) {
                $help = $originalStepConfig['help'];
            }
            $stepFinalConfig["help"] = $help;
        }
        return $stepFinalConfig;
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
