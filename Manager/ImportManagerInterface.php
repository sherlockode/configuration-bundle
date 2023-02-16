<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\File;

interface ImportManagerInterface
{
    /**
     * @param File $source
     */
    public function import(File $source): void;
}
