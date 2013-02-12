<?php
namespace Kitpages\ChainBundle\Tests\Console;

use Kitpages\ChainBundle\Tests\TestUtil\CommandTestCase;

class ConsoleTest extends CommandTestCase
{
    public function testRunCommandSimple()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-command CommandSample");
        $this->assertContains('changedByCommandConfig1', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-command CommandSampleOriginal");
        $this->assertContains('original', $output);
    }
    public function testRunCommandWithParameters()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-command CommandSample --p=return:titi");
        $this->assertContains('titi', $output);
    }
    public function testRunChainSimple()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChain");
        $this->assertContains('changedByCommandConfig1', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChainAndCommandParameter");
        $this->assertContains('ResultStandardChainAndCommandParameter', $output);
    }

    public function testRunChainComplex()
    {
        $client = self::createClient();

        $output = $this->runCommand($client, "kitpages:chain:run-chain CustomChainAndCommandParameter");
        $this->assertContains('ResultCustomChainAndCommandParameter', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain CustomChainAndTwoCommands");
        $this->assertContains('original', $output);

        $output = $this->runCommand($client, "kitpages:chain:run-chain StandardChainAndNewCommand");
        $this->assertContains('ResultStandardChainAndNewCommand', $output);
    }
}