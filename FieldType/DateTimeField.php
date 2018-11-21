<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DateTimeField extends AbstractField implements FieldTypeInterface
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
     * @return TransformerInterface
     */
    public function getModelTransformer()
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
