<?php
namespace Kitpages\ChainBundle\Tests\Chain;

use Kitpages\ChainBundle\Tests\Sample\CommandSample;
use Kitpages\ChainBundle\Service\CommandManager;
use Kitpages\ChainBundle\Service\ChainManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Log\NullLogger;

class ChainManagerTest extends WebTestCase
{
    private $logger = null;

    public function setUp()
    {
        $this->logger = $this->getMock('Symfony\Component\HttpKernel\Log\NullLogger');
    }

    public function testSamplePhpunit()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                )
            )
        );

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample'
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, true);
    }

    public function testWithCommandPhpunit()
    {

        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                    'commandTest' => array()
                )
            )
        );

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample'
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, true);
    }

    public function testWithCommandsPhpunit()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                    'commandTest' => array()
                )
            )
        );

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample'
            ),
            'commandTest2' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample'
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, true);
    }

    public function testWithParameterCommandPhpunit()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                    'commandTest' => array()
                )
            )
        );

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => false
                )
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, false);
    }

    public function testWithParameterChainPhpunit()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                    'commandTest' => array(
                        'parameter_list' => array(
                            'return' => false
                        )
                    )
                )
            )
        );

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => true
                )
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, false);
    }

    public function testWithParameterModifyPhpunit()
    {

        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                    'commandTest' => array(
                        'parameter_list' => array(
                            'return' => true
                        )
                    )
                )
            )
        );

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => true
                )
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $commandList = $chainTest->getCommandList();
        $commandList['commandTest']->setParameter('return', false);
        $chainTest->setCommandList($commandList);
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, false);
    }


}