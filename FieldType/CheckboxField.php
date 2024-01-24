<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\BooleanTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CheckboxField extends AbstractField
{
    public function getFormType(ParameterDefinition $definition): string
    {
        return CheckboxType::class;
    }

    public function getFormOptions(ParameterDefinition $definition): array
    {
        return [
            'required' => $definition->getOption('required', false),
        ];
    }

    public function getName(): string
    {
        return 'checkbox';
    }

    public function getModelTransformer(ParameterDefinition $definition): ?TransformerInterface
    {
        return new BooleanTransformer();
    }
}
