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
    public function getDefinedParameters();

    /**
     * Check a definition's existence
     *
     * @param string $path
     *
     * @return bool
     */
    public function has($path);

    /**
     * Get a definition from a path
     *
     * @param string $path
     *
     * @return ParameterDefinition
     */
    public function get($path);
}
