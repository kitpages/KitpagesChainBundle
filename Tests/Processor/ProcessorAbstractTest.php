<?php
namespace Kitpages\ChainBundle\Tests\Processor;

use Kitpages\ChainBundle\Tests\Sample\ProcessorSampleFromAbstract;
use Symfony\Component\DependencyInjection\Container;

class ProcessorAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }
    public function testContainer()
    {
        $processor = new ProcessorSampleFromAbstract();
        $container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $processor->setContainer($container);
        $container2 = $processor->getContainer();
        $this->assertTrue($container2 instanceof Container);
    }

    public function testParameters()
    {
        $processor = new ProcessorSampleFromAbstract();
        $res = $processor->getParameter("test");
        $this->assertEquals($res, null);

        $processor->setParameter("glou", "foo");
        $res = $res = $processor->getParameter("glou");
        $this->assertEquals($res, "foo");
    }

}