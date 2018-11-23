<?php

namespace Sherlockode\ConfigurationBundle\Form\Type;

use Sherlockode\ConfigurationBundle\Manager\ConfigurationManagerInterface;
use Sherlockode\ConfigurationBundle\Manager\FieldTypeManagerInterface;
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
            $formConfig = $this->getFormConfiguration($definition->getType());

            $baseOptions = [
                'label' => $definition->getLabel(),
                'translation_domain' => false,
            ];
            $childOptions = array_merge($baseOptions, $formConfig['options']);

            $builder
                ->add($definition->getPath(), $formConfig['type'], $childOptions)
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
