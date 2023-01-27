<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\Yaml\Yaml;

class ImportManager implements ImportManagerInterface
{
    /**
     * @var ParameterManagerInterface
     */
    private $parameterManager;

    /**
     * @var string
     */
    private $defaultSourceFile;

    /**
     * @param ParameterManagerInterface $parameterManager
     * @param string                    $defaultSourceFile
     */
    public function __construct(ParameterManagerInterface $parameterManager, string $defaultSourceFile)
    {
        $this->parameterManager = $parameterManager;
        $this->defaultSourceFile = $defaultSourceFile;
    }

    /**
     * @param string|null $filePath
     *
     * @throws \Exception
     */
    public function import(?string $filePath = null): void
    {
        $filePath = $this->getValidFilePath($filePath);
        $raw = file_get_contents($filePath);
        $data = Yaml::parse($raw);

        foreach ($data as $path => $value) {
            $this->parameterManager->set($path, $value);
        }

        $this->parameterManager->save();
    }

    /**
     * @param string|null $filePath
     *
     * @return string
     *
     * @throws \Exception
     */
    private function getValidFilePath(?string $filePath = null): string
    {
        if (null === $filePath) {
            $filePath = $this->defaultSourceFile;
        }

        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \Exception(sprintf('File "%s" does not exists or is not readable', $filePath));
        }

        return $filePath;
    }
}
