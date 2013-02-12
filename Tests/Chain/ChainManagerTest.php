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

    public function testChainCreationWithoutAnyCommand()
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
        $this->assertEquals($resultExecute, null);
    }

    public function testChainWithCommand()
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
        $this->assertEquals($resultExecute, "original");
    }

    public function testChainWithoutClassConfig()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'command_list' => array(
                    "commandTest" => array()
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
        $this->assertEquals($resultExecute, "original");
    }

    public function testWithTwoCommands()
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
        $this->assertEquals($resultExecute, "original");
    }

    public function testWithParameterCommand()
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
                    'return' => "changed"
                )
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "changed");
    }

    public function testWithParameterChangedInChain()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                    'commandTest' => array(
                        'parameter_list' => array(
                            'return' => "ChangedByChain"
                        )
                    )
                )
            )
        );

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => "ChangedByCommand"
                )
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "ChangedByChain");
    }

    public function testWithClassChangedInChain()
    {
        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                    'commandTest' => array(
                        'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample2'
                    )
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
        $this->assertEquals($resultExecute, "originalSample2");
    }

    public function testWithParameterModifyPhpunit()
    {

        $chainListConfig = array(
            'chainTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\ChainSample',
                'command_list' => array(
                    'commandTest' => array(
                        'parameter_list' => array(
                            'return' => "ChangedByChain"
                        )
                    )
                )
            )
        );

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => "ChangedByCommand"
                )
            )
        );

        $commandManager = new CommandManager($commandListConfig, null);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $this->logger);

        $chainTest = $chainManager->getChain('chainTest');
        $commandList = $chainTest->getCommandList();
        $commandList['commandTest']->setParameter('return', "ChangedManualy");
        $chainTest->setCommandList($commandList);
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, "ChangedManualy");
    }


}