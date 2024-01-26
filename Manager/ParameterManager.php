<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\ConfigurationBundle\Model\ParameterInterface;

/**
 * Class ParameterManager
 */
class ParameterManager implements ParameterManagerInterface
{
    private EntityManagerInterface $em;

    /**
     * Class name of the parameter entity
     */
    private string $class;

    /**
     * List of parameter objects
     *
     * @var ParameterInterface[]
     */
    private array $parameters;

    /**
     * Keep track of the new objects for the save action
     *
     * @var ParameterInterface[]
     */
    private array $newParameters;

    /**
     * Parameters with "real" types (not strings), ie. after transformation from the database
     *
     * Associative array with : path => value
     */
    private array $data;

    private bool $loaded;

    private ConfigurationManagerInterface $configurationManager;

    private ParameterConverterInterface $parameterConverter;

    /**
     * ParameterManager constructor.
     */
    public function __construct(
        EntityManagerInterface $em,
        string $class,
        ConfigurationManagerInterface $configurationManager,
        ParameterConverterInterface $parameterConverter
    ) {
        $this->em = $em;
        $this->class = $class;
        $this->configurationManager = $configurationManager;
        $this->parameters = [];
        $this->newParameters = [];
        $this->data = [];
        $this->loaded = false;
        $this->parameterConverter = $parameterConverter;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getAll(): array
    {
        if (false === $this->loaded) {
            $this->loadParameters();
        }

        foreach ($this->configurationManager->getDefinedParameters() as $path => $parameterDefinition) {
            if (!isset($this->data[$path])) {
                // check if the value exists in the DB
                if (isset($this->parameters[$path])) {
                    $value = $this->parameters[$path]->getValue();
                } else {
                    $value = $parameterDefinition->getDefaultValue();
                }

                // check that every parameter has been transformed to its user value
                if (!is_null($value)) {
                    $this->data[$path] = $this->parameterConverter->getUserValue($path, $value);
                }
            }
        }

        return $this->data;
    }

    public function get(string $path, mixed $default = null): mixed
    {
        if (false === $this->loaded) {
            $this->loadParameters();
        }

        if (isset($this->data[$path])) {
            return $this->data[$path];
        }

        $value = null;
        if (isset($this->parameters[$path])) {
            $value = $this->parameters[$path]->getValue();
        } else {
            $value = $this->configurationManager->get($path)->getDefaultValue();
        }

        // transform to user value only when the value is requested
        if (!is_null($value)) {
            $this->data[$path] = $this->parameterConverter->getUserValue($path, $value);
            return $this->data[$path];
        }

        return $value ?? $default;
    }

    public function set(string $path, mixed $value): self
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
        $stringValue = $this->parameterConverter->getStringValue($path, $value);
        $this->parameters[$path]->setValue($stringValue);
        $this->data[$path] = $this->parameterConverter->getUserValue($path, $stringValue);

        return $this;
    }

    /**
     * Save all parameters into the database
     */
    public function save(): void
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
    private function loadParameters(): void
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
}
