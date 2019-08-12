<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Form\Type\ImageType;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\ArrayTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;

class ImageField extends AbstractField
{
    /**
     * @return string
     */
    public function getFormType(ParameterDefinition $definition)
    {
        return ImageType::class;
    }

    public function getName()
    {
        return 'image';
    }

    /**
     * @param ParameterDefinition $definition
     *
     * @return TransformerInterface
     */
    public function getModelTransformer(ParameterDefinition $definition)
    {
        return new ArrayTransformer();
    }
}
