<?php

namespace Sherlockode\ConfigurationBundle\Form\Type;

use Sherlockode\ConfigurationBundle\Manager\ConfigurationManagerInterface;
use Sherlockode\ConfigurationBundle\Manager\FieldTypeManagerInterface;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ParametersType
 */
class ParametersType extends AbstractType
{
    /**
     * @var ConfigurationManagerInterface
     */
    private $configurationManager;

    /**
     * @var FieldTypeManagerInterface
     */
    private $fieldTypeManager;

    public function __construct(ConfigurationManagerInterface $configurationManager, FieldTypeManagerInterface $fieldTypeManager)
    {
        $this->fieldTypeManager = $fieldTypeManager;
        $this->configurationManager = $configurationManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->configurationManager->getDefinedParameters() as $definition) {
            $formConfig = $this->getFormConfiguration($definition);

            $baseOptions = [
                'label' => $definition->getLabel(),
                'required' => $definition->getOption('required', true),
                'translation_domain' => $definition->getTranslationDomain(),
            ];
            $childOptions = array_merge($baseOptions, $formConfig['options']);

            $builder
                ->add($definition->getPath(), $formConfig['type'], $childOptions)
            ;
        }
    }

    /**
     * @param ParameterDefinition $definition
     *
     * @return array
     */
    private function getFormConfiguration($definition)
    {
        $field = $this->fieldTypeManager->getField($definition->getType());

        return [
            'type' => $field->getFormType(),
            'options' => $field->getFormOptions($definition),
        ];
    }
}
