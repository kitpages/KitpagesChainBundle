<?php
namespace Kitpages\ChainBundle\Tests\Processor;

use Kitpages\ChainBundle\ChainException;
use Kitpages\ChainBundle\Processor\ProcessorManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ProcessorManagerTest extends WebTestCase
{
    public function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
    }
    public function testSimpleProcessor()
    {
        $processorListConfig = array(
            'processorTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ProcessorSample'
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);

        $processorTest = $processorManager->getProcessor('processorTest');
        $resultExecute = $processorTest->execute();
        $this->assertEquals($resultExecute, "original");
    }

    public function testProcessorWithParameter()
    {

        $processorListConfig = array(
            'processorTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ProcessorSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);

        $processorTest = $processorManager->getProcessor('processorTest');
        $resultExecute = $processorTest->execute();
        $this->assertEquals($resultExecute, "changed");
    }

    public function testProcessorExceptions()
    {
        $processorListConfig = array(
            'ProcessorThatDoesNotExist' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ProcessorThatDoesNotExist'
            ),
            'ProcessorWithoutInterface' => array(
                'class' => 'Kitpages\ChainBundle\KitpagesChainBundle'
            )
        );
        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);

        try {
            $processorTest = $processorManager->getProcessor('ProcessorThatDoesNotExist');
            $this->fail('No exception raised for ProcessorThatDoesNotExist');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }

        try {
            $processorTest = $processorManager->getProcessor('ProcessorWithoutInterface');
            $this->fail('No exception raised for ProcessorWithoutInterface');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }
    }
    public function testExtraProcessorListConfig()
    {
        $processorListConfig = array();

        $extraProcessorListConfig = array(
            'ProcessorThatDoesNotExist' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ProcessorThatDoesNotExist'
            ),
            'ProcessorWithoutInterface' => array(
                'class' => 'Kitpages\ChainBundle\KitpagesChainBundle'
            )
        );
        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);

        try {
            $processorTest = $processorManager->getProcessor('ProcessorNotDefined', array());
            $this->fail('No exception raised for ProcessorThatDoesNotExist');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }

        try {
            $processorTest = $processorManager->getProcessor('ProcessorThatDoesNotExist', $extraProcessorListConfig['ProcessorThatDoesNotExist']);
            $this->fail('No exception raised for ProcessorThatDoesNotExist');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }

        try {
            $processorTest = $processorManager->getProcessor('ProcessorWithoutInterface', $extraProcessorListConfig['ProcessorWithoutInterface']);
            $this->fail('No exception raised for ProcessorWithoutInterface');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }
    }

    public function testProcessorWithManualyChangedParameter()
    {

        $processorListConfig = array(
            'processorTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ProcessorSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);

        $processorTest = $processorManager->getProcessor('processorTest');
        $processorTest->setParameter('return', "changed2");
        $resultExecute = $processorTest->execute();
        $this->assertEquals($resultExecute, "changed2");
    }

    public function testProcessorWithConfigChangedParameter()
    {

        $processorListConfig = array(
            'processorTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ProcessorSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );
        $customChangedConfig = array(
            'class' => 'Kitpages\ChainBundle\Tests\Sample\ProcessorSample2',
            'parameter_list' => array(
                'return' => "configChanged"
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);

        $processorTest = $processorManager->getProcessor('processorTest', $customChangedConfig);
        $resultExecute = $processorTest->execute();
        $this->assertEquals($resultExecute, "configChanged");
    }

}