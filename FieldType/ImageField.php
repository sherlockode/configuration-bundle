<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Form\Type\ImageType;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\ArrayTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;

class ImageField extends AbstractField
{
    public function getFormType(ParameterDefinition $definition): string
    {
        return ImageType::class;
    }

    public function getName(): string
    {
        return 'image';
    }

    public function getModelTransformer(ParameterDefinition $definition): ?TransformerInterface
    {
        return new ArrayTransformer();
    }
}
