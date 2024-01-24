<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SimpleField extends AbstractField
{
    public function getFormType(ParameterDefinition $definition): string
    {
        $formType = $definition->getOption('subtype', TextType::class);

        if (!class_exists($formType)) {
            throw new \RuntimeException(sprintf('Undefined form type %s', $formType));
        }

        return $formType;
    }

    public function getName(): string
    {
        return 'simple';
    }
}
