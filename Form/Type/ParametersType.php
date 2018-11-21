<?php

namespace Sherlockode\ConfigurationBundle\Form\Type;

use Sherlockode\ConfigurationBundle\Manager\FieldTypeManager;
use Sherlockode\ConfigurationBundle\Manager\ParameterManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ParametersType
 */
class ParametersType extends AbstractType
{
    /**
     * @var ParameterManager
     */
    private $parameterManager;

    /**
     * @var FieldTypeManager
     */
    private $fieldTypeManager;

    public function __construct(ParameterManager $parameterManager, FieldTypeManager $fieldTypeManager)
    {
        $this->fieldTypeManager = $fieldTypeManager;
        $this->parameterManager = $parameterManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->parameterManager->getConfiguration() as $path => $config) {
            $formConfig = $this->getFormConfiguration($config['type']);

            $baseOptions = [
                'label' => $config['label'],
                'translation_domain' => false,
            ];
            $childOptions = array_merge($baseOptions, $formConfig['options']);

            $builder
                ->add($path, $formConfig['type'], $childOptions)
            ;
        }
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
        ];
    }
}
