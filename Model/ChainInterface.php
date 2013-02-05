<?php
namespace Kitpages\ChainBundle\Model;

interface ChainInterface
{
    function execute();

    function getCommandList();

    function setCommandConfigList($commandList);

    function setCommandManager($commandManager);

}