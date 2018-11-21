<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;

abstract class AbstractField implements FieldTypeInterface
{
    /**
     * @return array
     */
    public function getFormOptions()
    {
        return [];
    }

    /**
     * @return TransformerInterface
     */
    public function getModelTransformer()
    {
        return null;
    }
}
