<?php

namespace Sherlockode\ConfigurationBundle\Model;

interface ParameterInterface
{
    public function getId(): int;

    public function getPath(): string;

    public function setPath(string $path): self;

    /**
     * Return the value stored in the database.
     * This value is always a string. Use TransformerInterface for non-primary types
     */
    public function getValue(): string;

    public function setValue(string $value): self;
}
