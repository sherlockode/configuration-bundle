<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\BooleanTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CheckboxField extends AbstractField
{
    /**
     * @return string
     */
    public function getFormType(ParameterDefinition $definition)
    {
        return CheckboxType::class;
    }

    public function getFormOptions(ParameterDefinition $definition)
    {
        return [
            'required' => $definition->getOption('required', false),
        ];
    }

    public function getName()
    {
        return 'checkbox';
    }

    /**
     * @param ParameterDefinition $definition
     *
     * @return TransformerInterface
     */
    public function getModelTransformer(ParameterDefinition $definition)
    {
        return new BooleanTransformer();
    }
}
