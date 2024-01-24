<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadManager implements UploadManagerInterface
{
    private string $targetDir;

    private string $webPath;

    public function __construct(string $targetDir, string $webPath)
    {
        $this->targetDir = $targetDir;
        $this->webPath = $webPath;
    }

    /**
     * Upload file on server
     */
    public function upload(?UploadedFile $file = null): string
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
     */
    public function remove(string $filename): void
    {
        $filepath = $this->getFilePath($filename);

        if (!file_exists($filepath)) {
            return;
        }

        unlink($filepath);
    }

    public function getFilePath(string $filename): string
    {
        return $this->targetDir . DIRECTORY_SEPARATOR . $filename;
    }

    /**
     * Generate the filename to store on disk
     */
    private function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = str_replace('.' . $extension, '', $file->getClientOriginalName());

        $filename = $filename . '_' . md5(uniqid()) . '.' . $extension;

        return $filename;
    }

    public function resolveUri(string $filename): string
    {
        return $this->webPath . '/' . $filename;
    }
}
