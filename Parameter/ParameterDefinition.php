<?php

namespace Sherlockode\ConfigurationBundle\Parameter;

class ParameterDefinition
{
    private string $path;

    private string $type;

    private string $label;

    private ?string $defaultValue = null;

    private string $translationDomain;

    private array $options;

    /**
     * ParameterDefinition constructor.
     */
    public function __construct(string $path, string $type)
    {
        $this->path = $path;
        $this->type = $type;

        $this->label = $this->path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(?string $defaultValue): self
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    public function getTranslationDomain(): string
    {
        return $this->translationDomain;
    }

    public function setTranslationDomain(string $translationDomain): self
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    public function getOption(string $optionName, mixed $default = null): mixed
    {
        if (!isset($this->options[$optionName])) {
            return $default;
        }

        return $this->options[$optionName];
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
