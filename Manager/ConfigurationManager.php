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
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->definitions = $this->processConfiguration($config);
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
     * @param array $config
     *
     * @return ParameterDefinition[]
     */
    private function processConfiguration($config)
    {
        $result = [];
        foreach ($config as $path => $data) {
            $definition = new ParameterDefinition($path, $data['type'], ['label' => $data['label']]);
            $result[$path] = $definition;
        }

        return $result;
    }
}
