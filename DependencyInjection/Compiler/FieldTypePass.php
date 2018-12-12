<?php

namespace  Sherlockode\ConfigurationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FieldTypePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!class_exists('Doctrine\\ORM\\EntityManager')) {
            $container->removeDefinition('sherlockode_configuration.field.entity');
        }

        $definition = $container->findDefinition('sherlockode_configuration.field_manager');
        $taggedServices = $container->findTaggedServiceIds('sherlockode_configuration.field');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addFieldType', [new Reference($id)]);
        }
    }
}
