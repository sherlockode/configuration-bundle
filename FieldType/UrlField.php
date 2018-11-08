<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\UrlType;

class UrlField implements FieldTypeInterface
{
    /**
     * @return string
     */
    public function getFormType()
    {
        return UrlType::class;
    }

    public function getFormOptions()
    {
        return [];
    }

    public function getName()
    {
        return 'url';
    }
}
