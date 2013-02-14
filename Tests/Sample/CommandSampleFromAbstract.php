<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\CommandAbstract;

class CommandSampleFromAbstract
    extends CommandAbstract
{
    public function execute()
    {
        return "executed";
    }
}
