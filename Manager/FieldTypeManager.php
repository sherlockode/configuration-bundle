<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Sherlockode\ConfigurationBundle\FieldType\FieldTypeInterface;

class FieldTypeManager implements FieldTypeManagerInterface
{
    /**
     * @var FieldTypeInterface[]
     */
    private $fieldTypes;

    public function __construct()
    {
        $this->fieldTypes = [];
    }

    /**
     * @param FieldTypeInterface $fieldType
     *
     * @return $this
     */
    public function addFieldType(FieldTypeInterface $fieldType)
    {
        $this->fieldTypes[$fieldType->getName()] = $fieldType;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return FieldTypeInterface
     * @throws \Exception
     */
    public function getField($type)
    {
        if (!isset($this->fieldTypes[$type])) {
            throw new \Exception(sprintf('Unknown parameter type "%s"', $type));
        }

        return $this->fieldTypes[$type];
    }
}
