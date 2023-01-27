<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\File;

interface ExportManagerInterface
{
    /**
     * @param string|null $filePath
     *
     * @return File
     */
    public function export(?string $filePath = null): File;
}
