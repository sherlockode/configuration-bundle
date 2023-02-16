<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

class ImportManager implements ImportManagerInterface
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
}
