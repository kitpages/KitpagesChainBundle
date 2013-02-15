<?php
namespace Kitpages\ChainBundle\Tests\Chain;

use Kitpages\ChainBundle\Tests\Sample\ProcessorSample;
use Kitpages\ChainBundle\Processor\ProcessorManager;
use Kitpages\ChainBundle\Chain\ChainManager;
use Kitpages\ChainBundle\ChainException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ChainManagerTest extends WebTestCase
{
    private $logger = null;

    public function setUp()
    {
        $this->logger = $this->getMock('Symfony\Component\HttpKernel\Log\NullLogger');
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
    }

    public function testChainCreationWithoutAnyProcessor()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'processor_list' => array(
                )
            )
        );

        $processorListConfig = array(
            'processorTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample'
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, null);
    }

    public function testChainWithProcessor()
    {

        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'processor_list' => array(
                    'processorTest' => array()
                )
            )
        );

        $processorListConfig = array(
            'processorTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample'
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "original");
    }

    public function testChainWithoutClassConfig()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'processor_list' => array(
                    "processorTest" => array()
                )
            )
        );

        $processorListConfig = array(
            'processorTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample'
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "original");

        $processorList = $chainTest->getProcessorList();
        $this->assertEquals(count($processorList), 1);
        $this->assertTrue(array_key_exists("processorTest", $processorList));
    }

    public function testChainExceptions()
    {
        $chainListConfig = array(
            'ChainThatDoesNotExist' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainThatDoesNotExist'
            ),
            'ChainWithoutInterface' => array(
                'class' => '\Kitpages\ChainBundle\KitpagesChainBundle'
            )
        );
        $processorManager = new ProcessorManager(array(), $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        try {
            $chainTest = $chainManager->getChain('ChainThatDoesNotExist');
            $this->fail('No exception raised for ChainThatDoesNotExist');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }

        try {
            $chainTest = $chainManager->getChain('ChainWithoutInterface');
            $this->fail('No exception raised for ChainWithoutInterface');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }
    }

    public function testWithTwoProcessors()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'processor_list' => array(
                    'processorTest' => array()
                )
            )
        );

        $processorListConfig = array(
            'processorTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample'
            ),
            'processorTest2' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample'
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "original");
    }

    public function testWithParameterProcessor()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'processor_list' => array(
                    'processorTest' => array()
                )
            )
        );

        $processorListConfig = array(
            'processorTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "changed");
    }

    public function testWithParameterChangedInChain()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'processor_list' => array(
                    'processorTest' => array(
                        'parameter_list' => array(
                            'return' => "ChangedByChain"
                        )
                    )
                )
            )
        );

        $processorListConfig = array(
            'processorTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample',
                'parameter_list' => array(
                    'return' => "ChangedByProcessor"
                )
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "ChangedByChain");
    }

    public function testWithClassChangedInChain()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'processor_list' => array(
                    'processorTest' => array(
                        'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample2'
                    )
                )
            )
        );

        $processorListConfig = array(
            'processorTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample'
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "originalSample2");
    }

    public function testWithParameterModifyPhpunit()
    {

        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'processor_list' => array(
                    'processorTest' => array(
                        'parameter_list' => array(
                            'return' => "ChangedByChain"
                        )
                    )
                )
            )
        );

        $processorListConfig = array(
            'processorTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ProcessorSample',
                'parameter_list' => array(
                    'return' => "ChangedByProcessor"
                )
            )
        );

        $processorManager = new ProcessorManager($processorListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $processorManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $processorList = $chainTest->getProcessorList();
        $processorList['processorTest']->setParameter('return', "ChangedManualy");
        $chainTest->setProcessorList($processorList);
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute->getReturnValue(), "ChangedManualy");
    }


}