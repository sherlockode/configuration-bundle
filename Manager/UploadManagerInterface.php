<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadManagerInterface
{
    public function upload(?UploadedFile $file = null): string;

    public function remove(string $filename): void;

    public function resolveUri(string $filename): string;

    public function getFilePath(string $filename): string;
}
