<?php
namespace Kitpages\ChainBundle\Model;

interface ChainInterface
{
    public function execute();

    public function setProcessorList($processorList);

    public function getProcessorList();
}