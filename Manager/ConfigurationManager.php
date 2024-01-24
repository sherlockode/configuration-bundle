<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;

class ConfigurationManager implements ConfigurationManagerInterface
{
    /**
     * @var ParameterDefinition[]
     */
    private array $definitions;

    public function __construct(array $config = [], string|false $translationDomain = false)
    {
        $this->definitions = $this->processConfiguration($config, $translationDomain);
    }

    /**
     * Get the list of parameters in the configuration
     *
     * @return ParameterDefinition[]
     */
    public function getDefinedParameters(): array
    {
        return $this->definitions;
    }

    /**
     * @throws \Exception
     */
    public function get(string $path): ParameterDefinition
    {
        if (!isset($this->definitions[$path])) {
            throw new \Exception(sprintf('Undefined parameter path "%s"', $path));
        }

        return $this->definitions[$path];
    }

    public function has(string $path): bool
    {
        return isset($this->definitions[$path]);
    }

    /**
     * @return ParameterDefinition[]
     */
    private function processConfiguration(array $config, string|false $translationDomain = false): array
    {
        $result = [];
        foreach ($config as $path => $data) {
            if (!isset($data['type'])) {
                throw new \LogicException(sprintf('No type has been set for parameter "%s"', $path));
            }
            $definition = new ParameterDefinition($path, $data['type']);
            if (isset($data['label'])) {
                $definition->setLabel($data['label']);
            }
            if (!isset($data['translation_domain'])) {
                $data['translation_domain'] = $translationDomain;
            }
            if (isset($data['default_value'])) {
                $definition->setDefaultValue($data['default_value']);
            }
            $definition->setTranslationDomain($data['translation_domain']);
            if (isset($data['options']) && is_array($data['options'])) {
                $definition->setOptions($data['options']);
            }
            $result[$path] = $definition;
        }

        return $result;
    }
}
