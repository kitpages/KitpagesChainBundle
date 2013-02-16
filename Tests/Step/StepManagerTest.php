<?php
namespace Kitpages\ChainBundle\Tests\Step;

use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Step\StepManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;

class StepManagerTest extends WebTestCase
{
    public function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
    }
    public function testSimpleStep()
    {
        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample'
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);

        $stepTest = $stepManager->getStep('stepTest');
        $resultExecute = $stepTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "original");
    }

    public function testStepWithParameter()
    {

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);

        $stepTest = $stepManager->getStep('stepTest');
        $resultExecute = $stepTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "changed");
    }

    public function testStepExceptions()
    {
        $stepListConfig = array(
            'StepThatDoesNotExist' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepThatDoesNotExist'
            ),
            'StepWithoutInterface' => array(
                'class' => '\Kitpages\ChainBundle\KitpagesChainBundle'
            )
        );
        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);

        try {
            $stepTest = $stepManager->getStep('StepThatDoesNotExist');
            $this->fail('No exception raised for StepThatDoesNotExist');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }

        try {
            $stepTest = $stepManager->getStep('StepWithoutInterface');
            $this->fail('No exception raised for StepWithoutInterface');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }
    }
    public function testExtraStepListConfig()
    {
        $stepListConfig = array();

        $extraStepListConfig = array(
            'StepThatDoesNotExist' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepThatDoesNotExist'
            ),
            'StepWithoutInterface' => array(
                'class' => '\Kitpages\ChainBundle\KitpagesChainBundle'
            )
        );
        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);

        try {
            $stepTest = $stepManager->getStep('StepNotDefined', array());
            $this->fail('No exception raised for StepThatDoesNotExist');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }

        try {
            $stepTest = $stepManager->getStep('StepThatDoesNotExist', $extraStepListConfig['StepThatDoesNotExist']);
            $this->fail('No exception raised for StepThatDoesNotExist');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }

        try {
            $stepTest = $stepManager->getStep('StepWithoutInterface', $extraStepListConfig['StepWithoutInterface']);
            $this->fail('No exception raised for StepWithoutInterface');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }
    }

    public function testStepWithManualyChangedParameter()
    {

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);

        $stepTest = $stepManager->getStep('stepTest');
        $stepTest->setParameter('return', "changed2");
        $resultExecute = $stepTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "changed2");
    }

    public function testStepWithConfigChangedParameter()
    {

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );
        $customChangedConfig = array(
            'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample2',
            'parameter_list' => array(
                'return' => "configChanged"
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);

        $stepTest = $stepManager->getStep('stepTest', $customChangedConfig);
        $resultExecute = $stepTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "configChanged");
    }

}