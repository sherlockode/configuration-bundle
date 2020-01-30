<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadManager implements UploadManagerInterface
{
    /**
     * @var string
     */
    private $targetDir;

    /**
     * @var string
     */
    private $webPath;

    public function __construct($targetDir, $webPath)
    {
        $this->targetDir = $targetDir;
        $this->webPath = $webPath;
    }

    /**
     * Upload file on server
     *
     * @param UploadedFile|null $file
     *
     * @return string
     */
    public function upload(UploadedFile $file = null)
    {
        if ($file === null) {
            return '';
        }

        $filename = $this->generateFilename($file);
        $file->move($this->targetDir, $filename);

        return $filename;
    }

    /**
     * Remove file
     *
     * @param string $filename
     */
    public function remove($filename)
    {
        $filepath = $this->getFilePath($filename);

        if (!file_exists($filepath)) {
            return;
        }

        unlink($filepath);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getFilePath($filename)
    {
        return $this->targetDir . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Generate the filename to store on disk
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    private function generateFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace('.' . $extension, '', $file->getClientOriginalName());

        $filename = $filename . '_' . md5(uniqid()) . '.' . $extension;

        return $filename;
    }

    public function resolveUri($filename)
    {
        return $this->webPath . '/' . $filename;
    }
}
