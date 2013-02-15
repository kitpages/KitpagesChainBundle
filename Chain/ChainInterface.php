<?php
namespace Kitpages\ChainBundle\Chain;

interface ChainInterface
{
    public function execute();

    public function setProcessorList($processorList);

    public function getProcessorList();
}