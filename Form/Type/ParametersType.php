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
            $field = $this->fieldTypeManager->getField($definition->getType());

            $baseOptions = [
                'label' => $definition->getLabel(),
                'required' => $definition->getOption('required', true),
                'attr' => $definition->getOption('attr', []),
                'row_attr' => $definition->getOption('row_attr', []),
                'label_attr' => $definition->getOption('label_attr', []),
                'translation_domain' => $definition->getTranslationDomain(),
            ];
            $childOptions = array_merge($baseOptions, $field->getFormOptions($definition));

            $builder
                ->add($definition->getPath(), $field->getFormType($definition), $childOptions)
            ;
        }
    }
}
