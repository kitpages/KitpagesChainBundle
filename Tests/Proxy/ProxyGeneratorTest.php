<?php
namespace Kitpages\ChainBundle\Tests\Proxy;

use Kitpages\ChainBundle\Tests\Sample\StepSample;
use Kitpages\ChainBundle\Proxy\ProxyGenerator;
use Kitpages\ChainBundle\Proxy\ProxyInterface;

class ProxyGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }
    public function testProxyInfo()
    {
        $proxyGenerator = new ProxyGenerator();
        $originalClassName = '\Kitpages\ChainBundle\Tests\Sample\StepSample';

        $this->assertEquals(
            $proxyGenerator->getProxyNameSpace($originalClassName),
            'Kitpages\ChainBundle\Proxy\Kitpages\ChainBundle\Tests\Sample'
        );
        $this->assertEquals(
            $proxyGenerator->getProxyClassName($originalClassName),
            '\Kitpages\ChainBundle\Proxy\Kitpages\ChainBundle\Tests\Sample\StepSample'
        );
    }

    public function testProxyClassGeneration()
    {
        $proxyGenerator = new ProxyGenerator();
        $originalClassName = '\Kitpages\ChainBundle\Tests\Sample\StepSample';

        // this test is used to check if proxy class generated in the previous test
        // is generated again or not.
        $className = $proxyGenerator->generateProxyClass(
            $originalClassName,
            'class <<proxyClassName>> {}',
            array("proxyClassName"=>'\Kitpages\ChainBundle\Proxy\Kitpages\ChainBundle\Tests\Sample\StepSample')
        );

        $this->assertEquals(
            $className,
            '\Kitpages\ChainBundle\Proxy\Kitpages\ChainBundle\Tests\Sample\StepSample'
        );
    }

    public function testProxyGeneration()
    {
        $proxyGenerator = new ProxyGenerator();
        $originalClassName = '\Kitpages\ChainBundle\Tests\Sample\StepSample';

        $proxyClassName = $proxyGenerator->generateProcessProxyClass($originalClassName);

        $proxy = new $proxyClassName();

        $this->assertTrue($proxy instanceof StepSample);
        $this->assertTrue($proxy instanceof ProxyInterface);
    }

    public function testProxyGenerationTwice()
    {
        $proxyGenerator = new ProxyGenerator();
        $originalClassName = '\Kitpages\ChainBundle\Tests\Sample\StepSample';

        $proxyClassName = $proxyGenerator->generateProcessProxyClass($originalClassName);
        $proxy = new $proxyClassName();

        $this->assertTrue($proxy instanceof StepSample);
        $this->assertTrue($proxy instanceof ProxyInterface);

        $proxyClassName = $proxyGenerator->generateProcessProxyClass($originalClassName);
        $proxy = new $proxyClassName();
        $this->assertTrue($proxy instanceof ProxyInterface);
    }

}