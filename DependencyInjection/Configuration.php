<?php

namespace Sherlockode\ConfigurationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root = $builder->root('sherlockode_configuration');

        $root
            ->fixXmlConfig('parameter')
            ->children()
                ->arrayNode('entity_class')
                    ->isRequired()
                    ->children()
                        ->scalarNode('parameter')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('parameters')
                    ->useAttributeAsKey('path')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('type')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $builder;
    }
}
