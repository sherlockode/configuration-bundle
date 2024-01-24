<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;

interface ConfigurationManagerInterface
{
    /**
     * Get the list of parameters in the configuration
     *
     * @return ParameterDefinition[]
     */
    public function getDefinedParameters(): array;

    /**
     * Check a definition's existence
     */
    public function has(string $path): bool;

    /**
     * Get a definition from a path
     */
    public function get(string $path): ParameterDefinition;
}
