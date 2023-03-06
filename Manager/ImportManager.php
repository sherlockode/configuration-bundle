<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Secrets\AbstractVault;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

class ImportManager implements ImportManagerInterface
{
    /**
     * @var ParameterManagerInterface
     */
    private $parameterManager;

    /**
     * @var AbstractVault
     */
    private $vault;

    /**
     * @param ParameterManagerInterface $parameterManager
     * @param AbstractVault             $vault
     */
    public function __construct(ParameterManagerInterface $parameterManager, AbstractVault $vault)
    {
        $this->parameterManager = $parameterManager;
        $this->vault = $vault;
    }

    /**
     * @param File $source
     */
    public function import(File $source): void
    {
        $raw = Yaml::parseFile($source->getRealPath());

        foreach ($raw as $key => $value) {
            $this->parameterManager->set($key, $value);
        }

        $this->parameterManager->save();
        unlink($source->getRealPath());
    }

    /**
     * @return void
     */
    public function importFromVault(): void
    {
        foreach ($this->parameterManager->getAll() as $path => $value) {
            $stringValue = $this->vault->reveal($path);
            $this->parameterManager->set($path, $this->parameterManager->getUserValue($path, $stringValue));
        }

        $this->parameterManager->save();
    }
}
