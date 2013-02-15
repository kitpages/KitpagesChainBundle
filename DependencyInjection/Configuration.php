<?php

namespace Kitpages\ChainBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration processer.
 * Parses/validates the extension configuration and sets default values.
 *
 * @author Philippe Le Van (@plv)
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('kitpages_chain');

        $this->addProcessorListSection($rootNode);
        //$this->addChainListSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Parses the kitpages_cms.block config section
     * Example for yaml driver:
     * kitpages_cms:
     *     block:
     *         template:
     *             template_list: {standard: \Kitpages\CmsBundle\Form\TemplateStandardType}
     *
     * @param ArrayNodeDefinition $node
     * @return void
     */
    private function addProcessorListSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('processor_list')
                    ->useAttributeAsKey('processor_slug')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('parameter_list')
                                ->useAttributeAsKey('parameter_slug')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('chain_list')
                    ->useAttributeAsKey('chain_slug')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')
                                ->defaultValue('\Kitpages\ChainBundle\Chain\Chain')
                            ->end()
                            ->arrayNode('processor_list')
                                ->useAttributeAsKey('processor_slug')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('class')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->arrayNode('parameter_list')
                                            ->defaultValue(array())
                                            ->useAttributeAsKey('parameter_slug')
                                            ->prototype('scalar')
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

}