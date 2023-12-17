<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DateTimeField extends AbstractField
{
    public function getFormType(ParameterDefinition $definition): string
    {
        return DateTimeType::class;
    }

    public function getName(): string
    {
        return 'datetime';
    }

    public function getModelTransformer(ParameterDefinition $definition): ?TransformerInterface
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
