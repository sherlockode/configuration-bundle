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
        $container->setParameter('sherlockode_configuration.translation_domain', $config['translation_domain']);
        $container->setParameter('sherlockode_configuration.templates.edit_form', $config['templates']['edit_form']);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new XmlFileLoader($container, $fileLocator);
        $loader->load('services.xml');
        $loader->load('field_types.xml');

        $container->registerForAutoconfiguration(FieldTypeInterface::class)
            ->addTag('sherlockode_configuration.field');

        $targetDir = $config['upload']['directory'] ?? sys_get_temp_dir();
        $webPath = $config['upload']['uri_prefix'] ?? '/';

        $uploadManager = $container->getDefinition('sherlockode_configuration.upload_manager');
        if ($uploadManager->getClass() == 'Sherlockode\\ConfigurationBundle\\Manager\\UploadManager') {
            $uploadManager->setArguments([
                $targetDir,
                $webPath,
            ]);
        }

        $this->registerFormTheme($container);
        $this->registerExportParameters($container, $config['export']);
    }

    private function registerFormTheme(ContainerBuilder $container): void
    {
        $resources = $container->hasParameter('twig.form.resources') ?
            $container->getParameter('twig.form.resources') : [];

        \array_unshift($resources, '@SherlockodeConfiguration/form.html.twig');
        $container->setParameter('twig.form.resources', $resources);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function registerExportParameters(ContainerBuilder $container, array $config): void
    {
        $exportDir = rtrim($config['directory'], DIRECTORY_SEPARATOR);
        $fileName = trim($config['file_name'], DIRECTORY_SEPARATOR);

        if (0 !== strpos($exportDir, DIRECTORY_SEPARATOR)) {
            $projectDir = rtrim($container->getParameter('kernel.project_dir'), DIRECTORY_SEPARATOR);
            $exportDir = sprintf('%s/%s', $projectDir, $exportDir);
        }

        $container->setParameter(
            'sherlockode_configuration.export.file_path',
            sprintf('%s/%s', $exportDir, $fileName)
        );
    }
}
