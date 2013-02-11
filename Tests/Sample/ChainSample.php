<?php
namespace Kitpages\ChainBundle\Tests\Sample;

use Kitpages\ChainBundle\Model\Chain;
use Kitpages\ChainBundle\ChainException;

class ChainSample extends Chain
{

    public function execute()
    {
        $return[] = true;
        foreach($this->commandList as $command) {
            $return[] = $command->execute();
        }

        return (array_search(false, $return) == false)?true:false;
    }

}