<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\UrlType;

class UrlField extends AbstractField implements FieldTypeInterface
{
    /**
     * @return string
     */
    public function getFormType()
    {
        return UrlType::class;
    }

    public function getName()
    {
        return 'url';
    }
}
