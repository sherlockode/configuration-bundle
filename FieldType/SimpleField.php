<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SimpleField extends AbstractField
{
    /**
     * @return string
     */
    public function getFormType(ParameterDefinition $definition)
    {
        $formType = $definition->getOption('subtype', TextType::class);

        if (!class_exists($formType)) {
            throw new \RuntimeException(sprintf('Undefined form type %s', $formType));
        }

        return $formType;
    }

    public function getName()
    {
        return 'simple';
    }
}
