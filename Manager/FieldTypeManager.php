<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Sherlockode\ConfigurationBundle\FieldType\FieldTypeInterface;

class FieldTypeManager implements FieldTypeManagerInterface
{
    /**
     * @var FieldTypeInterface[]
     */
    private array $fieldTypes;

    public function __construct()
    {
        $this->fieldTypes = [];
    }

    public function addFieldType(FieldTypeInterface $fieldType): self
    {
        $this->fieldTypes[$fieldType->getName()] = $fieldType;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function getField(string $type): FieldTypeInterface
    {
        if (!isset($this->fieldTypes[$type])) {
            throw new \Exception(sprintf('Unknown parameter type "%s"', $type));
        }

        return $this->fieldTypes[$type];
    }
}
