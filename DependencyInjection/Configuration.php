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

        $this->addStepListSection($rootNode);
        $this->addChainListSection($rootNode);

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
    private function addStepListSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('shared_step_list')
                    ->useAttributeAsKey('shared_step_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('parent_shared_step')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('class')
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('parameter_list')
                                ->defaultValue(array())
                                ->useAttributeAsKey('parameter_name')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->arrayNode('service_list')
                                ->defaultValue(array())
                                ->useAttributeAsKey('service_name')
                                ->prototype('scalar')
                                ->end()
                            ->end()
                            ->arrayNode('help')
                                ->children()
                                    ->scalarNode('short')
                                        ->cannotBeEmpty()
                                    ->end()
                                    ->scalarNode('complete')
                                        ->cannotBeEmpty()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function addChainListSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('chain_list')
                    ->useAttributeAsKey('chain_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('class')
                                ->defaultValue('\Kitpages\ChainBundle\Chain\Chain')
                            ->end()
                            ->arrayNode('step_list')
                                ->useAttributeAsKey('step_name')
                                ->prototype('array')
                                    ->children()
                                        ->scalarNode('parent_shared_step')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->scalarNode('class')
                                            ->cannotBeEmpty()
                                        ->end()
                                        ->arrayNode('parameter_list')
                                            ->defaultValue(array())
                                            ->useAttributeAsKey('parameter_name')
                                            ->prototype('scalar')
                                            ->end()
                                        ->end()
                                        ->arrayNode('service_list')
                                            ->defaultValue(array())
                                            ->useAttributeAsKey('service_name')
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