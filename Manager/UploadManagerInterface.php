<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadManagerInterface
{
    /**
     * @param UploadedFile|null $file
     *
     * @return string
     */
    public function upload(UploadedFile $file = null);

    /**
     * @param string $filename
     */
    public function remove($filename);

    /**
     * @param string $filename
     *
     * @return string
     */
    public function resolveUri($filename);

    /**
     * @param string $filename
     *
     * @return string
     */
    public function getFilePath($filename);
}
