<?php

namespace Sherlockode\ConfigurationBundle\Manager;

use Sherlockode\ConfigurationBundle\FieldType\FieldTypeInterface;

interface FieldTypeManagerInterface
{
    /**
     * @param FieldTypeInterface $fieldType
     *
     * @return $this
     */
    public function addFieldType(FieldTypeInterface $fieldType);

    /**
     * @param string $type
     *
     * @return FieldTypeInterface
     */
    public function getField($type);
}
