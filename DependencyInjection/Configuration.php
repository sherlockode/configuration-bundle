<?php

namespace Sherlockode\ConfigurationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sherlockode_configuration');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->fixXmlConfig('parameter')
            ->children()
                ->scalarNode('translation_domain')->defaultFalse()->end()
                ->arrayNode('entity_class')
                    ->isRequired()
                    ->children()
                        ->scalarNode('parameter')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('edit_form')
                            ->cannotBeEmpty()
                            ->defaultValue('@SherlockodeConfiguration/parameters.html.twig')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('parameters')
                    ->useAttributeAsKey('path')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('type')->isRequired()->end()
                            ->scalarNode('translation_domain')->defaultNull()->end()
                            ->variableNode('options')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('upload')
                    ->children()
                        ->scalarNode('directory')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('uri_prefix')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('export')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('directory')
                            ->cannotBeEmpty()
                            ->defaultValue('var/configuration')
                        ->end()
                        ->scalarNode('file_name')
                            ->cannotBeEmpty()
                            ->defaultValue('configuration.yaml')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
