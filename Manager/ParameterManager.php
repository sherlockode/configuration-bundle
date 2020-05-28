<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\ConfigurationBundle\Model\ParameterInterface;

/**
 * Class ParameterManager
 */
class ParameterManager implements ParameterManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Class name of the parameter entity
     *
     * @var string
     */
    private $class;

    /**
     * List of parameter objects
     *
     * @var ParameterInterface[]
     */
    private $parameters;

    /**
     * Keep track of the new objects for the save action
     *
     * @var ParameterInterface[]
     */
    private $newParameters;

    /**
     * Parameters with "real" types (not strings), ie. after transformation from the database
     *
     * Associative array with : path => value
     *
     * @var array
     */
    private $data;

    /**
     * @var bool
     */
    private $loaded;

    /**
     * @var ConfigurationManagerInterface[]
     */
    private $configurationManager;

    /**
     * @var FieldTypeManagerInterface
     */
    private $fieldTypeManager;

    /**
     * ParameterManager constructor.
     *
     * @param EntityManagerInterface        $em
     * @param string                        $class
     * @param ConfigurationManagerInterface $configurationManager
     * @param FieldTypeManagerInterface     $fieldTypeManager
     */
    public function __construct(
        EntityManagerInterface $em,
        $class,
        ConfigurationManagerInterface $configurationManager,
        FieldTypeManagerInterface $fieldTypeManager
    ) {
        $this->em = $em;
        $this->class = $class;
        $this->configurationManager = $configurationManager;
        $this->parameters = [];
        $this->newParameters = [];
        $this->data = [];
        $this->loaded = false;
        $this->fieldTypeManager = $fieldTypeManager;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        if (false === $this->loaded) {
            $this->loadParameters();
        }
        // check that every parameter has been transformed to its user value
        foreach ($this->parameters as $path => $parameter) {
            if (!isset($this->data[$path])) {
                $this->data[$parameter->getPath()] = $this->getUserValue($parameter->getPath(), $parameter->getValue());
            }
        }

        return $this->data;
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

        if (isset($this->data[$path])) {
            return $this->data[$path];
        } elseif (isset($this->parameters[$path])) {
            // transform to user value only when the value is requested
            $this->data[$path] = $this->getUserValue($path, $this->parameters[$path]->getValue());

            return $this->data[$path];
        }

        return $default;
    }

    /**
     * @param string $path
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($path, $value)
    {
        if (false === $this->loaded) {
            $this->loadParameters();
        }
        if (!$this->configurationManager->has($path)) {
            return $this;
        }
        if (!isset($this->parameters[$path])) {
            /** @var ParameterInterface $parameter */
            $parameter = new $this->class();
            $parameter->setPath($path);
            $this->parameters[$path] = $parameter;
            $this->newParameters[] = $parameter;
        }
        $stringValue = $this->getStringValue($path, $value);
        $this->parameters[$path]->setValue($stringValue);
        $this->data[$path] = $this->getUserValue($path, $stringValue);

        return $this;
    }

    /**
     * Save all parameters into the database
     */
    public function save()
    {
        foreach ($this->newParameters as $parameter) {
            $this->em->persist($parameter);
        }
        $this->em->flush($this->parameters);
    }

    /**
     * Load the parameters from the database
     * and transform their string value into the expected type
     */
    private function loadParameters()
    {
        $parameters = $this->em->getRepository($this->class)->findAll();
        /** @var ParameterInterface $parameter */
        foreach ($parameters as $parameter) {
            if (!$this->configurationManager->has($parameter->getPath())) {
                continue;
            }
            $this->parameters[$parameter->getPath()] = $parameter;
        }
        $this->loaded = true;
    }

    /**
     * @param string $path
     * @param mixed  $value
     *
     * @return string
     */
    private function getStringValue($path, $value)
    {
        $parameterConfig = $this->configurationManager->get($path);
        $fieldType = $this->fieldTypeManager->getField($parameterConfig->getType());

        if ($transformer = $fieldType->getModelTransformer($parameterConfig)) {
            $value = $transformer->reverseTransform($value);
        }

        return $value;
    }

    /**
     * @param string $path
     * @param string $value
     *
     * @return mixed
     */
    private function getUserValue($path, $value)
    {
        $parameterDefinition = $this->configurationManager->get($path);
        $fieldType = $this->fieldTypeManager->getField($parameterDefinition->getType());

        if ($transformer = $fieldType->getModelTransformer($parameterDefinition)) {
            $value = $transformer->transform($value);
        }

        return $value;
    }
}
