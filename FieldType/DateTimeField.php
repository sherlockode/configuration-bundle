<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DateTimeField extends AbstractField
{
    /**
     * @return string
     */
    public function getFormType(ParameterDefinition $definition)
    {
        return DateTimeType::class;
    }

    public function getName()
    {
        return 'datetime';
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

                return \DateTime::createFromFormat(\DateTime::ATOM, $data);
            },
            function ($data) {
                if ($data instanceof \DateTime) {
                    return $data->format(\DateTime::ATOM);
                }

                return '';
            }
        );
    }
}
