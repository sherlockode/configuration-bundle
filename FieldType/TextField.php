<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

class TextField extends AbstractField implements FieldTypeInterface
{
    /**
     * @return string
     */
    public function getFormType()
    {
        return TextType::class;
    }

    public function getName()
    {
        return 'text';
    }
}
