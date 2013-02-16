<?php
namespace Kitpages\ChainBundle\Tests\Console;

use Kitpages\ChainBundle\Tests\TestUtil\CommandTestCase;

class ConsoleTest extends CommandTestCase
{
    public function testRunCommandSimple()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-step StepSample");
        $this->assertContains('changedByStepConfig1', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-step StepSampleOriginal");
        $this->assertContains('original', $output);
    }
    public function testRunCommandWithParameters()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-step StepSample --p=return:titi");
        $this->assertContains('titi', $output);
    }
    public function testRunChainSimple()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChain");
        $this->assertContains('changedByStepConfig1', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChainAndStepParameter");
        $this->assertContains('ResultStandardChainAndStepParameter', $output);
    }

    public function testRunChainComplex()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-chain CustomChainAndStepParameter");
        $this->assertContains('ResultCustomChainAndStepParameter', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain CustomChainAndTwoSteps");
        $this->assertContains('original', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChainAndNewStep");
        $this->assertContains('ResultStandardChainAndNewStep', $output);
    }
}