<?php
namespace Kitpages\ChainBundle\Tests\Command;

use Kitpages\ChainBundle\Tests\Sample\CommandSample;
use Kitpages\ChainBundle\Service\CommandManager;

class CommandManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testPhpunit()
    {
        $command = new CommandSample();
        $commandListConfig = array(

        );
        $commandManager = new CommandManager($commandListConfig);
    }
}