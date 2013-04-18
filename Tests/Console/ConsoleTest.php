<?php
namespace Kitpages\ChainBundle\Tests\Console;

use Kitpages\ChainBundle\Tests\TestUtil\CommandTestCase;
use Kitpages\ChainBundle\ChainException;

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
    public function testInheritanceStep()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-step childStep");
        $this->assertContains('changedByStepConfig1', $output);
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

    public function testStopPropagation()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-chain CustomStopPropagation");
        $this->assertContains('ResultStopPropagation', $output);
    }

    public function testPreventDefault()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-step CustomPreventDefault");
        $this->assertContains("unit test exception", $output);

        $output = $this->runCommand($client, "kitpages:chain:run-step CustomPreventDefault --p=isDefaultPrevented:true");
        $this->assertContains("output=null", $output);
    }

}