<?php
namespace Kitpages\ChainBundle\Tests\Console;

use Kitpages\ChainBundle\Tests\TestUtil\CommandTestCase;

class ConsoleTest extends CommandTestCase
{
    public function testRunCommandSimple()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-processor ProcessorSample");
        $this->assertContains('changedByProcessorConfig1', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-processor ProcessorSampleOriginal");
        $this->assertContains('original', $output);
    }
    public function testRunCommandWithParameters()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-processor ProcessorSample --p=return:titi");
        $this->assertContains('titi', $output);
    }
    public function testRunChainSimple()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChain");
        $this->assertContains('changedByProcessorConfig1', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChainAndProcessorParameter");
        $this->assertContains('ResultStandardChainAndProcessorParameter', $output);
    }

    public function testRunChainComplex()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-chain CustomChainAndProcessorParameter");
        $this->assertContains('ResultCustomChainAndProcessorParameter', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain CustomChainAndTwoProcessors");
        $this->assertContains('original', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChainAndNewProcessor");
        $this->assertContains('ResultStandardChainAndNewProcessor', $output);
    }
}