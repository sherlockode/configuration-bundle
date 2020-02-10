<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;

class ConfigurationManager implements ConfigurationManagerInterface
{
    /**
     * @var ParameterDefinition[]
     */
    private $definitions;

    /**
     * @param array        $config
     * @param string|false $translationDomain
     */
    public function __construct($config = [], $translationDomain = false)
    {
        $this->definitions = $this->processConfiguration($config, $translationDomain);
    }

    /**
     * Get the list of parameters in the configuration
     *
     * @return ParameterDefinition[]
     */
    public function getDefinedParameters()
    {
        return $this->definitions;
    }

    /**
     * @param string $path
     *
     * @return ParameterDefinition
     * @throws \Exception
     */
    public function get($path)
    {
        if (!isset($this->definitions[$path])) {
            throw new \Exception(sprintf('Undefined parameter path "%s"', $path));
        }

        return $this->definitions[$path];
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public function has($path)
    {
        return isset($this->definitions[$path]);
    }

    /**
     * @param array        $config
     * @param string|false $translationDomain
     *
     * @return ParameterDefinition[]
     */
    private function processConfiguration($config, $translationDomain = false)
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
            $definition->setTranslationDomain($data['translation_domain']);
            if (isset($data['options']) && is_array($data['options'])) {
                $definition->setOptions($data['options']);
            }
            $result[$path] = $definition;
        }

        return $result;
    }
}
