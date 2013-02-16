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