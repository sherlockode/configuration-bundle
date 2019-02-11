<?php

namespace Sherlockode\ConfigurationBundle\Parameter;

class ParameterDefinition
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $translationDomain;

    /**
     * @var array
     */
    private $options;

    /**
     * ParameterDefinition constructor.
     *
     * @param string $path
     * @param string $type
     */
    public function __construct($path, $type)
    {
        $this->path = $path;
        $this->type = $type;

        $this->label = $this->path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    /**
     * @param string $translationDomain
     *
     * @return $this
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;

        return $this;
    }

    /**
     * @param string $optionName
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOption($optionName, $default = null)
    {
        if (!isset($this->options[$optionName])) {
            return $default;
        }

        return $this->options[$optionName];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}
