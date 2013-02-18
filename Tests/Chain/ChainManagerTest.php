<?php
namespace Kitpages\ChainBundle\Tests\Chain;

use Kitpages\ChainBundle\Tests\Sample\StepSample;
use Kitpages\ChainBundle\Step\StepManager;
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

    public function testChainCreationWithoutAnyStep()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'step_list' => array(
                )
            )
        );

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample'
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, null);
    }

    public function testChainWithStep()
    {

        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'step_list' => array(
                    'stepTestLabel' => array(
                        'parent_shared_step_name' => 'stepTest'
                    )
                )
            )
        );

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample'
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "original");
    }

    public function testChainWithoutClassConfig()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'step_list' => array(
                    "stepTestLabel" => array(
                        'parent_shared_step_name' => 'stepTest'
                    )
                )
            )
        );

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample'
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "original");

        $stepList = $chainTest->getStepList();
        $this->assertEquals(count($stepList), 1);
        $this->assertTrue(array_key_exists("stepTestLabel", $stepList));
    }

    public function testChainExceptions()
    {
        $chainListConfig = array(
            'ChainThatDoesNotExistLabel' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainThatDoesNotExist'
            ),
            'ChainWithoutInterfaceLabel' => array(
                'class' => '\Kitpages\ChainBundle\KitpagesChainBundle'
            )
        );
        $stepManager = new StepManager(array(), $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        try {
            $chainTest = $chainManager->getChain('ChainThatDoesNotExistLabel');
            $this->fail('No exception raised for ChainThatDoesNotExistLabel');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }

        try {
            $chainTest = $chainManager->getChain('ChainWithoutInterfaceLabel');
            $this->fail('No exception raised for ChainWithoutInterfaceLabel');
        } catch (ChainException $e) {
            $this->assertTrue(true);
        }
    }

    public function testWithTwoSteps()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'step_list' => array(
                    'stepTestLabel' => array(
                        'parent_shared_step_name' => 'stepTest'
                    ),
                    'stepTestLabel2' => array(
                        'parent_shared_step_name' => 'stepTest'
                    )

                )
            )
        );

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample'
            ),
            'stepTest2' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample'
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "original");
    }

    public function testWithParameterStep()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'step_list' => array(
                    'stepTestLabel' => array(
                        'parent_shared_step_name' => 'stepTest'
                    )
                )
            )
        );

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "changed");
    }

    public function testWithParameterChangedInChain()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'step_list' => array(
                    'stepTestLabel' => array(
                        'parent_shared_step_name' => 'stepTest',
                        'parameter_list' => array(
                            'return' => "ChangedByChain"
                        )
                    )
                )
            )
        );

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample',
                'parameter_list' => array(
                    'return' => "ChangedByStep"
                )
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "ChangedByChain");
    }

    public function testWithClassChangedInChain()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'step_list' => array(
                    'stepTest' => array(
                        'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample2'
                    )
                )
            )
        );

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample'
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "originalSample2");
    }

    public function testWithParameterModifyPhpunit()
    {

        $chainListConfig = array(
            'chainTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'step_list' => array(
                    'stepTestLabel' => array(
                        'parent_shared_step_name' => 'stepTest',
                        'parameter_list' => array(
                            'return' => "ChangedByChain"
                        )
                    )
                )
            )
        );

        $stepListConfig = array(
            'stepTest' => array(
                'class' => '\Kitpages\ChainBundle\Tests\Sample\StepSample',
                'parameter_list' => array(
                    'return' => "ChangedByStep"
                )
            )
        );

        $stepManager = new StepManager($stepListConfig, $this->container, $this->eventDispatcher);
        $chainManager = new ChainManager($chainListConfig, $stepManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $stepList = $chainTest->getStepList();
        $stepList['stepTestLabel']->setParameter('return', "ChangedManualy");
        $chainTest->setStepList($stepList);
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "ChangedManualy");
    }


}