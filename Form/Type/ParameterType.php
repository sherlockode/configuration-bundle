<?php

namespace Sherlockode\ConfigurationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formConfig = $this->getFormConfiguration($options['parameter_field_type']);

        $childOptions = array_merge(['label' => $options['label']], $formConfig['options']);

        $builder
            ->add('value', $formConfig['type'], $childOptions)
        ;

        $builder->addViewTransformer(new CallbackTransformer(function ($data) {
            return $data;
        }, function ($data) use ($builder) {
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
     * @return string
     * @throws \Exception
     */
    private function getFormConfiguration($type)
    {
        $availableTypes = [
            'text' => ['type' => TextType::class, 'options' => []],
        ];

        if (!isset($availableTypes[$type])) {
            throw new \Exception(sprintf('Unknown parameter type "%s"', $type));
        }

        return $availableTypes[$type];
    }
}
