<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\ArrayTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChoiceField extends AbstractField
{
    public function getFormType(ParameterDefinition $definition): string
    {
        return ChoiceType::class;
    }

    public function getFormOptions(ParameterDefinition $definition): array
    {
        return [
            'choices' => $definition->getOption('choices', []),
            'multiple' => $definition->getOption('multiple', false),
            'expanded' => $definition->getOption('expanded', false),
            'placeholder' => $definition->getOption('placeholder', null),
        ];
    }

    public function getName(): string
    {
        return 'choice';
    }

    public function getModelTransformer(ParameterDefinition $definition): ?TransformerInterface
    {
        if ($definition->getOption('multiple', false)) {
            return new ArrayTransformer();
        }

        return null;
    }
}
