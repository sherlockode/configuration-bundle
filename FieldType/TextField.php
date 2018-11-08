<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextField extends Field implements FieldTypeInterface
{
    /**
     * @return string
     */
    public function getFormType()
    {
        return TextType::class;
    }

    public function getFormOptions()
    {
        return [];
    }

    public function getName()
    {
        return 'text';
    }
}
