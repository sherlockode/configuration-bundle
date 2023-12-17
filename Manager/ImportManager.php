<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Secrets\AbstractVault;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

class ImportManager implements ImportManagerInterface
{
    private ParameterManagerInterface $parameterManager;

    private AbstractVault $vault;

    public function __construct(ParameterManagerInterface $parameterManager, AbstractVault $vault)
    {
        $this->parameterManager = $parameterManager;
        $this->vault = $vault;
    }

    public function import(File $source): void
    {
        $raw = Yaml::parseFile($source->getRealPath());

        foreach ($raw as $path => $stringValue) {
            $this->parameterManager->set($path, $this->parameterManager->getUserValue($path, $stringValue));
        }

        $this->parameterManager->save();
        unlink($source->getRealPath());
    }

    public function importFromVault(): void
    {
        foreach ($this->parameterManager->getAll() as $path => $value) {
            $stringValue = $this->vault->reveal($path);
            $this->parameterManager->set($path, $this->parameterManager->getUserValue($path, $stringValue));
        }

        $this->parameterManager->save();
    }
}
