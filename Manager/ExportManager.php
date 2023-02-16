<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\Yaml\Yaml;

class ExportManager implements ExportManagerInterface
{
    /**
     * @var ParameterManagerInterface
     */
    private $parameterManager;

    /**
     * @param ParameterManagerInterface $parameterManager
     */
    public function __construct(ParameterManagerInterface $parameterManager)
    {
        $this->parameterManager = $parameterManager;
    }

    /**
     * @return string
     */
    public function exportAsString(): string
    {
        $parameters = $this->parameterManager->getAll();

        return Yaml::dump($parameters);
    }
}
