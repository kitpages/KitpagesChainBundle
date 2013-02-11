<?php
namespace Kitpages\ChainBundle\Tests\Command;

use Kitpages\ChainBundle\Tests\Sample\CommandSample;
use Kitpages\ChainBundle\Service\CommandManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandManagerTest extends WebTestCase
{
    public function setUp()
    {
        $client = static::createClient();
        $this->container = $client->getContainer();
    }
    public function testSamplePhpunit()
    {
        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample'
            )
        );

        $commandManager = new CommandManager($commandListConfig, $this->container);

        $commandTest = $commandManager->getCommand('commandTest');
        $resultExecute = $commandTest->execute();
        $this->assertEquals($resultExecute, true);
    }

    public function testParameterPhpunit()
    {

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => false
                )
            )
        );

        $container = new ContainerBuilder();

        $commandManager = new CommandManager($commandListConfig, $this->container);

        $commandTest = $commandManager->getCommand('commandTest');
        $resultExecute = $commandTest->execute();
        $this->assertEquals($resultExecute, false);
    }

    public function testParameterModifyPhpunit()
    {

        $commandListConfig = array(
            'commandTest' => array(
                'class' => 'Kitpages\ChainBundle\Tests\Sample\CommandSample',
                'parameter_list' => array(
                    'return' => true
                )
            )
        );

        $container = new ContainerBuilder();

        $commandManager = new CommandManager($commandListConfig, $this->container);

        $commandTest = $commandManager->getCommand('commandTest');
        $commandTest->setParameter('return', false);
        $resultExecute = $commandTest->execute();
        $this->assertEquals($resultExecute, false);
    }
}