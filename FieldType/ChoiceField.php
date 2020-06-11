<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\ArrayTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChoiceField extends AbstractField
{
    /**
     * @return string
     */
    public function getFormType(ParameterDefinition $definition)
    {
        return ChoiceType::class;
    }

    public function getFormOptions(ParameterDefinition $definition)
    {
        return [
            'choices' => $definition->getOption('choices', []),
            'multiple' => $definition->getOption('multiple', false),
            'expanded' => $definition->getOption('expanded', false),
            'placeholder' => $definition->getOption('placeholder', null),
        ];
    }

    public function getName()
    {
        return 'choice';
    }

    /**
     * @param ParameterDefinition $definition
     *
     * @return TransformerInterface
     */
    public function getModelTransformer(ParameterDefinition $definition)
    {
        if ($definition->getOption('multiple', false)) {
            return new ArrayTransformer();
        }

        return null;
    }
}
