<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DateTimeField extends Field implements FieldTypeInterface
{
    /**
     * @return string
     */
    public function getFormType()
    {
        return DateTimeType::class;
    }

    public function getFormOptions()
    {
        return [];
    }

    public function getName()
    {
        return 'datetime';
    }

    /**
     * @return DataTransformerInterface
     */
    public function getModelTransformer()
    {
        return new CallbackTransformer(
            function ($data) {
                if ($data->getValue()) {
                    $data->setValue(\DateTime::createFromFormat(\DateTime::ATOM, $data->getValue()));
                } else {
                    $data->setValue(null);
                }

                return $data;
            },
            function ($data) {
                if ($data->getValue() instanceof \DateTime) {
                    $value = $data->getValue()->format(\DateTime::ATOM);
                    $data->setValue($value);
                }

                return $data;
            }
        );
    }
}
