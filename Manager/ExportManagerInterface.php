<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ExportManagerInterface
{
    public function exportAsString(): string;

    public function exportInVault(): void;
}
