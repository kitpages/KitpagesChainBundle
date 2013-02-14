<?php
namespace Kitpages\ChainBundle\Tests\Command;

use Kitpages\ChainBundle\Tests\Sample\CommandSampleFromAbstract;
use Symfony\Component\DependencyInjection\Container;

class CommandAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }
    public function testContainer()
    {
        $command = new CommandSampleFromAbstract();
        $container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $command->setContainer($container);
        $container2 = $command->getContainer();
        $this->assertTrue($container2 instanceof Container);
    }

    public function testParameters()
    {
        $command = new CommandSampleFromAbstract();
        $res = $command->getParameter("test");
        $this->assertEquals($res, null);

        $command->setParameter("glou", "foo");
        $res = $res = $command->getParameter("glou");
        $this->assertEquals($res, "foo");
    }

}