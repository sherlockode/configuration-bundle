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

    public function __construct($path, $type, array $config = [])
    {
        $this->path = $path;
        $this->type = $type;

        if (isset($config['label'])) {
            $this->label = $config['label'];
        } else {
            $this->label = $this->path;
        }
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getType()
    {
        return $this->type;
    }
}
