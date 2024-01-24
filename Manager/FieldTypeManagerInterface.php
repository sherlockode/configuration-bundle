<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Sherlockode\ConfigurationBundle\FieldType\FieldTypeInterface;

interface FieldTypeManagerInterface
{
    public function addFieldType(FieldTypeInterface $fieldType): self;

    public function getField(string $type): FieldTypeInterface;
}
