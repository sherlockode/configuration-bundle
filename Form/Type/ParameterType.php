<?php

namespace Sherlockode\ConfigurationBundle\Form\Type;

use Sherlockode\ConfigurationBundle\Manager\FieldTypeManager;
use Sherlockode\ConfigurationBundle\Model\ParameterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ParameterType
 */
class ParameterType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var FieldTypeManager
     */
    private $fieldTypeManager;

    /**
     * @param string           $class
     * @param FieldTypeManager $fieldTypeManager
     */
    public function __construct($class, FieldTypeManager $fieldTypeManager)
    {
        $this->class = $class;
        $this->fieldTypeManager = $fieldTypeManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formConfig = $this->getFormConfiguration($options['parameter_field_type']);

        $childOptions = array_merge(['label' => $options['label']], $formConfig['options']);

        $builder
            ->add('value', $formConfig['type'], $childOptions)
        ;

        if ($formConfig['model_transformer']) {
            $builder->addModelTransformer($formConfig['model_transformer']);
        }

        $builder->addViewTransformer(new CallbackTransformer(function ($data) {
            return $data;
        }, function ($data) use ($builder) {
            /** @var ParameterInterface $data */
            $data->setPath($builder->getName());

            return $data;
        }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', $this->class);
        $resolver->setRequired('parameter_field_type');
    }

    /**
     * @param string $type
     *
     * @return array
     */
    private function getFormConfiguration($type)
    {
        $field = $this->fieldTypeManager->getField($type);

        return [
            'type' => $field->getFormType(),
            'options' => $field->getFormOptions(),
            'model_transformer' => $field->getModelTransformer(),
        ];
    }
}
