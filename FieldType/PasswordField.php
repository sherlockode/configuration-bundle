<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordField extends AbstractField
{
    private EntityManagerInterface $em;

    private string $parameterClass;

    public function __construct(EntityManagerInterface $em, string $parameterClass)
    {
        $this->em = $em;
        $this->parameterClass = $parameterClass;
    }

    public function getFormType(ParameterDefinition $definition): string
    {
        return PasswordType::class;
    }

    public function getName(): string
    {
        return 'password';
    }

    public function getModelTransformer(ParameterDefinition $definition): ?TransformerInterface
    {
        $parameter = $this->em->getRepository($this->parameterClass)->findOneBy(['path' => $definition->getPath()]);
        $currentValue = $parameter ? $parameter->getValue() : null;

        return new CallbackTransformer(function ($data) {
            return $data;
        }, function ($data) use ($currentValue) {
            if (!$data && $currentValue) {
                return $currentValue;
            }

            return $data;
        });
    }
}
