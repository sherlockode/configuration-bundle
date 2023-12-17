<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;

abstract class AbstractField implements FieldTypeInterface
{
    public function getFormOptions(ParameterDefinition $definition): array
    {
        return [];
    }

    public function getModelTransformer(ParameterDefinition $definition): ?TransformerInterface
    {
        return null;
    }
}
