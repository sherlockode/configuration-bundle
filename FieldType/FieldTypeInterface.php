<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

interface FieldTypeInterface
{
    /**
     * @return string
     */
    public function getFormType();

    /**
     * @return array
     */
    public function getFormOptions();

    /**
     * @return string
     */
    public function getName();
}
