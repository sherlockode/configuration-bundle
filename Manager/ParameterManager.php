<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Sherlockode\ConfigurationBundle\Model\ParameterInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ParameterManager
 */
class ParameterManager
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var bool
     */
    private $loaded;

    /**
     * @var array
     */
    private $config;

    /**
     * ParameterManager constructor.
     *
     * @param ObjectManager $om
     * @param string        $class
     * @param array         $config
     */
    public function __construct(ObjectManager $om, $class, $config)
    {
        $this->om = $om;
        $this->class = $class;
        $this->config = $config;
        $this->parameters = [];
        $this->loaded = false;
    }

    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $path
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($path, $default = null)
    {
        if (false === $this->loaded) {
            $this->loadParameters();
        }

        if (isset($this->parameters[$path])) {
            return $this->parameters[$path];
        }

        return $default;
    }

    private function loadParameters()
    {
        $parameters = $this->om->getRepository($this->class)->findAll();
        /** @var ParameterInterface $parameter */
        foreach ($parameters as $parameter) {
            $this->parameters[$parameter->getPath()] = $parameter->getValue();
        }
        $this->loaded = true;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->config;
    }
}
