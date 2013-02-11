<?php
namespace Kitpages\ChainBundle\Tests\Command;

use Kitpages\ChainBundle\Tests\Sample\CommandSample;
use Kitpages\ChainBundle\Service\CommandManager;
use Kitpages\ChainBundle\Service\ChainManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChainManagerTest extends WebTestCase
{
    public function setUp()
    {
        $client = static::createClient();
        $this->container = $client->getContainer();
    }

    public function testSamplePhpunit()
    {

        $client = static::createClient();
        $container = $client->getContainer();

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

        $commandManager = new CommandManager($commandListConfig, $this->container);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $container->get('logger'));

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, true);
    }

    public function testWithCommandPhpunit()
    {

        $client = $this->createClient();
        $container = $client->getContainer();

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

        $commandManager = new CommandManager($commandListConfig, $this->container);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $container->get('logger'));

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, true);
    }

    public function testWithCommandsPhpunit()
    {

        $client = $this->createClient();
        $container = $client->getContainer();

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

        $commandManager = new CommandManager($commandListConfig, $this->container);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $container->get('logger'));

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, true);
    }

    public function testWithParameterCommandPhpunit()
    {

        $client = $this->createClient();
        $container = $client->getContainer();

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

        $commandManager = new CommandManager($commandListConfig, $this->container);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $container->get('logger'));

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, false);
    }

    public function testWithParameterChainPhpunit()
    {

        $client = $this->createClient();
        $container = $client->getContainer();

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

        $commandManager = new CommandManager($commandListConfig, $this->container);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $container->get('logger'));

        $chainTest = $chainManager->getChain('chainTest');
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, false);
    }

    public function testWithParameterModifyPhpunit()
    {

        $client = $this->createClient();
        $container = $client->getContainer();

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

        $commandManager = new CommandManager($commandListConfig, $this->container);
        $chainManager = new ChainManager($chainListConfig, $commandManager, $container->get('logger'));

        $chainTest = $chainManager->getChain('chainTest');
        $commandList = $chainTest->getCommandList();
        $commandList['commandTest']->setParameter('return', false);
        $chainTest->setCommandList($commandList);
        $resultExecute = $chainTest->execute();
        $this->assertEquals($resultExecute, false);
    }


}