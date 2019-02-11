<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;

interface FieldTypeInterface
{
    /**
     * @return string
     */
    public function getFormType(ParameterDefinition $definition);

    /**
     * @param ParameterDefinition $definition
     *
     * @return array
     */
    public function getFormOptions(ParameterDefinition $definition);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param ParameterDefinition $definition
     *
     * @return TransformerInterface
     */
    public function getModelTransformer(ParameterDefinition $definition);
}
