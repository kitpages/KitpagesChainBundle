<?php
namespace Kitpages\ChainBundle\Tests\Step;

use Kitpages\ChainBundle\Tests\Sample\StepSampleFromAbstract;
use Symfony\Component\DependencyInjection\Container;

class StepAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }
    public function testContainer()
    {
        $step = new StepSampleFromAbstract();
        $container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $step->setContainer($container);
        $container2 = $step->getContainer();
        $this->assertTrue($container2 instanceof Container);
    }

    public function testParameters()
    {
        $step = new StepSampleFromAbstract();
        $res = $step->getParameter("test");
        $this->assertEquals($res, null);

        $step->setParameter("glou", "foo");
        $res = $res = $step->getParameter("glou");
        $this->assertEquals($res, "foo");
    }

}