<?php

namespace Sherlockode\ConfigurationBundle\Model;

class Parameter implements ParameterInterface
{
    protected int $id;

    protected string $path;

    protected ?string $value = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
