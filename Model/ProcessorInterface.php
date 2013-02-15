<?php
namespace Kitpages\ChainBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;

interface ProcessorInterface
{
    public function setParameter($key, $value);

    public function execute();

    public function setContainer(ContainerInterface $container);
}