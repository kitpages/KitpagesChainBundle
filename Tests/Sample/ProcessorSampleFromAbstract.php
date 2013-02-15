<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\ProcessorAbstract;

class ProcessorSampleFromAbstract
    extends ProcessorAbstract
{
    public function execute()
    {
        return "executed";
    }
}
