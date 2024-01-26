<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ParameterManagerInterface
{
    /**
     * Get all parameter values
     *
     * Return an associative array as : path => value
     */
    public function getAll(): array;

    /**
     * Save all parameters into the database
     */
    public function save(): void;

    /**
     * Get an existing parameter value for a given path
     */
    public function get(string $path, mixed $default = null): mixed;

    /**
     * Set the value of a parameter at given path
     */
    public function set(string $path, mixed $value): self;
}
