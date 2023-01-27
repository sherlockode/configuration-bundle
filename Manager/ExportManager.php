<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Yaml\Yaml;

class ExportManager implements ExportManagerInterface
{
    /**
     * @var ParameterManagerInterface
     */
    private $parameterManager;

    /**
     * @var string
     */
    private $defaultTargetFile;

    /**
     * @param ParameterManagerInterface $parameterManager
     * @param string                    $defaultTargetFile
     */
    public function __construct(ParameterManagerInterface $parameterManager, string $defaultTargetFile)
    {
        $this->parameterManager = $parameterManager;
        $this->defaultTargetFile = $defaultTargetFile;
    }

    /**
     * @param string|null $filePath
     *
     * @return File
     *
     * @throws \Exception
     */
    public function export(?string $filePath = null): File
    {
        $filePath = $this->getValidFilePath($filePath);
        $parameters = $this->parameterManager->getAll();

        file_put_contents($filePath, Yaml::dump($parameters));

        return new File($filePath);
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
        if (null !== $filePath) {
            $directory = dirname($filePath);

            if (!is_writeable($directory)) {
                throw new \Exception(sprintf('Directory "%s" is not writeable', $directory));
            }

            return $filePath;
        }

        $targetDirectory = dirname($this->defaultTargetFile);

        if (!is_dir($targetDirectory)) {
            if (!mkdir($targetDirectory)) {
                throw new \Exception(sprintf('Directory "%s" does not exists', $targetDirectory));
            }
        }

        if (!is_writeable($targetDirectory)) {
            throw new \Exception(sprintf('Directory "%s" is not writeable', $targetDirectory));
        }

        return $this->defaultTargetFile;
    }
}
