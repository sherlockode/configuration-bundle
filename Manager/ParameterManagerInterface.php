<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ParameterManagerInterface
{
    /**
     * Get an existing parameter value for a given path
     *
     * @param string $path
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($path, $default = null);

    /**
     * Set the value of a parameter at given path
     *
     * @param string $path
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($path, $value);
}
