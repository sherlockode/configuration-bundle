<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ImportManagerInterface
{
    /**
     * @param string|null $filePath
     */
    public function import(?string $filePath = null): void;
}
