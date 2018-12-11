<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;

abstract class AbstractField implements FieldTypeInterface
{
    /**
     * @param ParameterDefinition $definition
     *
     * @return array
     */
    public function getFormOptions(ParameterDefinition $definition)
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
