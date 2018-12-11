<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TextareaField extends AbstractField implements FieldTypeInterface
{
    /**
     * @return string
     */
    public function getFormType()
    {
        return TextareaType::class;
    }

    public function getName()
    {
        return 'textarea';
    }
}
