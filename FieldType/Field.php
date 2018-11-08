<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

abstract class Field
{
    /**
     * @return DataTransformerInterface
     */
    public function getModelTransformer()
    {
        return null;
    }
}
