<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Bundle\FrameworkBundle\Secrets\AbstractVault;
use Symfony\Component\Yaml\Yaml;

class ExportManager implements ExportManagerInterface
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
     * @return string
     */
    public function exportAsString(): string
    {
        $parameters = $this->parameterManager->getAll();

        return Yaml::dump($parameters);
    }

    /**
     * @return void
     *
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
