<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;

interface FieldTypeInterface
{
    public function getFormType(ParameterDefinition $definition): string;

    public function getFormOptions(ParameterDefinition $definition): array;

    public function getName(): string;

    public function getModelTransformer(ParameterDefinition $definition): ?TransformerInterface;
}
