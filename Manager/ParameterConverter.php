<?php

namespace Sherlockode\ConfigurationBundle\Manager;

class ParameterConverter implements ParameterConverterInterface
{
    public function __construct(
        private ConfigurationManagerInterface $configurationManager,
        private FieldTypeManagerInterface $fieldTypeManager,
    ) {
    }

    public function getStringValue(string $path, mixed $value): ?string
    {
        $parameterConfig = $this->configurationManager->get($path);
        $fieldType = $this->fieldTypeManager->getField($parameterConfig->getType());

        if ($transformer = $fieldType->getModelTransformer($parameterConfig)) {
            $value = $transformer->reverseTransform($value);
        }

        return $value;
    }

    public function getUserValue(string $path, ?string $value): mixed
    {
        $parameterDefinition = $this->configurationManager->get($path);
        $fieldType = $this->fieldTypeManager->getField($parameterDefinition->getType());

        if ($transformer = $fieldType->getModelTransformer($parameterDefinition)) {
            $value = $transformer->transform($value);
        }

        return $value;
    }
}
