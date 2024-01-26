<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ParameterConverterInterface
{
    /**
     * Get value as string
     */
    public function getStringValue(string $path, mixed $value): ?string;

    /**
     * Get user value
     */
    public function getUserValue(string $path, string $value): mixed;
}
