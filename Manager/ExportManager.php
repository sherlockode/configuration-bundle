<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Secrets\AbstractVault;
use Symfony\Component\Yaml\Yaml;

class ExportManager implements ExportManagerInterface
{
    private ParameterManagerInterface $parameterManager;

    private AbstractVault $vault;

    public function __construct(ParameterManagerInterface $parameterManager, AbstractVault $vault)
    {
        $this->parameterManager = $parameterManager;
        $this->vault = $vault;
    }

    public function exportAsString(): string
    {
        $parameters = [];
        foreach ($this->parameterManager->getAll() as $path => $value) {
            $stringValue = $this->parameterManager->getStringValue($path, $value);

            if (null !== $stringValue) {
                $parameters[$path] = $stringValue;
            }
        }

        return Yaml::dump($parameters);
    }

    /**
     * @throws \Exception
     */
    public function exportInVault(): void
    {
        foreach ($this->parameterManager->getAll() as $path => $value) {
            $stringValue = $this->parameterManager->getStringValue($path, $value);

            if (null !== $stringValue) {
                $this->vault->seal($path, $stringValue);
            }
        }
    }
}
