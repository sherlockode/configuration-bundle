<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;

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

    /**
     * @return TransformerInterface
     */
    public function getModelTransformer();
}
