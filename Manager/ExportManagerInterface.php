<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ExportManagerInterface
{
    /**
     * @return string
     */
    public function exportAsString(): string;
}
