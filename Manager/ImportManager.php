<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Secrets\AbstractVault;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

class ImportManager implements ImportManagerInterface
{
    private ParameterManagerInterface $parameterManager;

    private ParameterConverterInterface $parameterConverter;

    private ConfigurationManager $configurationManager;

    private AbstractVault $vault;

    public function __construct(ParameterManagerInterface $parameterManager, ParameterConverterInterface $parameterConverter, ConfigurationManager $configurationManager, AbstractVault $vault)
    {
        $this->parameterManager = $parameterManager;
        $this->parameterConverter = $parameterConverter;
        $this->configurationManager = $configurationManager;
        $this->vault = $vault;
    }

    public function import(File $source): void
    {
        $raw = Yaml::parseFile($source->getRealPath());

        foreach ($raw as $path => $stringValue) {
            if ($this->configurationManager->has($path)) {
                $this->parameterManager->set($path, $this->parameterConverter->getUserValue($path, $stringValue));
            }
        }

        $this->parameterManager->save();
        unlink($source->getRealPath());
    }

    public function importFromVault(): void
    {
        foreach ($this->configurationManager->getDefinedParameters() as $definition) {
            $stringValue = $this->vault->reveal($definition->getPath());

            if (null !== $stringValue) {
                $this->parameterManager->set($definition->getPath(), $this->parameterConverter->getUserValue($definition->getPath(), $stringValue));
            }
        }

        $this->parameterManager->save();
    }
}
