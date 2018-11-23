<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ParameterManagerInterface
{
    /**
     * Get all parameter values
     *
     * Return an associative array as : path => value
     *
     * @return array
     */
    public function getAll();

    /**
     * Save all parameters into the database
     */
    public function save();

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
