<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Symfony\Component\HttpFoundation\File\File;

interface ImportManagerInterface
{
    public function import(File $source): void;

    public function importFromVault(): void;
}
