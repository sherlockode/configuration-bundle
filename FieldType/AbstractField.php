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
     * @param ParameterDefinition $definition
     *
     * @return TransformerInterface
     */
    public function getModelTransformer(ParameterDefinition $definition)
    {
        return null;
    }
}
