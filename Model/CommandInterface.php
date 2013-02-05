<?php
namespace Kitpages\ChainBundle\Model;

interface CommandInterface
{
    function setParameter($parameter, $value);

    function setParameterList($parameterList);

    function execute();

    function getSlug();

    function setContainer($cntainer);

    function getContainer();

}