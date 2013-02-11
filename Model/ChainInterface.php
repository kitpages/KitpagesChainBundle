<?php
namespace Kitpages\ChainBundle\Model;

interface ChainInterface
{
    public function execute();

    public function setCommandList($commandList);

}