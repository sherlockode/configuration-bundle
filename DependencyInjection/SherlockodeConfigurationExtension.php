<?php

namespace Sherlockode\ConfigurationBundle\DependencyInjection;

use Sherlockode\ConfigurationBundle\FieldType\FieldTypeInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SherlockodeConfigurationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);


        $container->setParameter('sherlockode_configuration.parameter_class', $config['entity_class']['parameter']);
        $container->setParameter('sherlockode_configuration.parameters', $config['parameters']);
        $container->setParameter('sherlockode_configuration.templates.edit_form', $config['templates']['edit_form']);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new XmlFileLoader($container, $fileLocator);
        $loader->load('services.xml');
        $loader->load('field_types.xml');

        if (method_exists($container, 'registerForAutoconfiguration')) {
            $container->registerForAutoconfiguration(FieldTypeInterface::class)
                ->addTag('sherlockode_configuration.field');
        }
    }
}
