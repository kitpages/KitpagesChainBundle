<?php
namespace Kitpages\ChainBundle\Tests\Command;

use Kitpages\ChainBundle\Tests\Sample\CommandSample;
use Kitpages\ChainBundle\Service\CommandManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class CommandManagerTest extends WebTestCase
{
    public function setUp()
    {
        $this->container = $this->getMock('Symfony\Component\DependencyInjection\Container');
    }
    public function testSimpleCommand()
    {
        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample'
            )
        );

        $commandManager = new CommandManager($commandListConfig, $this->container);

        $commandTest = $commandManager->getCommand('commandTest');
        $resultExecute = $commandTest->execute();
        $this->assertEquals($resultExecute, "original");
    }

    public function testCommandWithParameter()
    {

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );

        $commandManager = new CommandManager($commandListConfig, $this->container);

        $commandTest = $commandManager->getCommand('commandTest');
        $resultExecute = $commandTest->execute();
        $this->assertEquals($resultExecute, "changed");
    }

    public function testCommandWithManualyChangedParameter()
    {

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );

        $commandManager = new CommandManager($commandListConfig, $this->container);

        $commandTest = $commandManager->getCommand('commandTest');
        $commandTest->setParameter('return', "changed2");
        $resultExecute = $commandTest->execute();
        $this->assertEquals($resultExecute, "changed2");
    }

    public function testCommandWithConfigChangedParameter()
    {

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => "changed"
                )
            )
        );
        $customChangedConfig = array(
            'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample2',
            'parameter_list' => array(
                'return' => "configChanged"
            )
        );

        $commandManager = new CommandManager($commandListConfig, $this->container);

        $commandTest = $commandManager->getCommand('commandTest', $customChangedConfig);
        $resultExecute = $commandTest->execute();
        $this->assertEquals($resultExecute, "configChanged");
    }

}