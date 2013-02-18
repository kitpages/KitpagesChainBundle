<?php
namespace Kitpages\ChainBundle\Tests\Step;

use Kitpages\ChainBundle\Tests\Sample\StepSampleFromAbstract;
use Symfony\Component\DependencyInjection\Container;

class StepAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }
    public function testService()
    {
        $step = new StepSampleFromAbstract();
        $container = $this->getMock('Symfony\Component\DependencyInjection\Container');
        $step->setService("container", $container);
        $container2 = $step->getService("container");
        $this->assertTrue($container2 instanceof Container);
        $noService = $step->getService("no-service");
        $this->assertNull($noService);
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

    public function testRenderedParameter()
    {
        $step = new StepSampleFromAbstract();
        $step->setParameter("command", "ls {{file}} {{bar}} {{not-defined}}");
        $step->setParameter("file", "toto.pdf");
        $step->setParameter("bar", "foo");
        $result = $step->getRenderedParameter("command", function($str) {return strtoupper($str);});
        $this->assertEquals( "ls TOTO.PDF FOO ", $result);
        $result2 = $step->getRenderedParameter("command");
        $this->assertEquals( "ls toto.pdf foo ", $result2);
    }

}