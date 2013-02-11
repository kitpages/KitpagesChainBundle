<?php
namespace Kitpages\ChainBundle\Model;

interface CommandInterface
{
    public function setParameter($parameter, $value);

    public function setParameterList($parameterList);

    public function execute();

    public function setContainer($container);
}