<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
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
        return new CallbackTransformer(
            function ($data) {
                if (!$data) {
                    return null;
                }
                if (false !== ($unserialized = @unserialize($data))) {
                    return $unserialized;
                }

                return $data;
            },
            function ($data) {
                if (is_array($data)) {
                    return serialize($data);
                }

                return $data;
            }
        );
    }
}
