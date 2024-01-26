<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Secrets\AbstractVault;
use Symfony\Component\Yaml\Yaml;

class ExportManager implements ExportManagerInterface
{
    private ParameterManagerInterface $parameterManager;

    private ParameterConverterInterface $parameterConverter;

    private AbstractVault $vault;

    public function __construct(ParameterManagerInterface $parameterManager, ParameterConverterInterface $parameterConverter, AbstractVault $vault)
    {
        $this->parameterManager = $parameterManager;
        $this->parameterConverter = $parameterConverter;
        $this->vault = $vault;
    }

    public function exportAsString(): string
    {
        $parameters = [];
        foreach ($this->parameterManager->getAll() as $path => $value) {
            $stringValue = $this->parameterConverter->getStringValue($path, $value);

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
            $stringValue = $this->parameterConverter->getStringValue($path, $value);

            if (null !== $stringValue) {
                $this->vault->seal($path, $stringValue);
            }
        }
    }
}
