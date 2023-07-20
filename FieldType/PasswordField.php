<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordField extends AbstractField
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $parameterClass;

    /**
     * @param EntityManagerInterface $em
     * @param string                 $parameterClass
     */
    public function __construct(EntityManagerInterface $em, $parameterClass)
    {
        $this->em = $em;
        $this->parameterClass = $parameterClass;
    }

    /**
     * @return string
     */
    public function getFormType(ParameterDefinition $definition)
    {
        return PasswordType::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'password';
    }

    /**
     * @param ParameterDefinition $definition
     *
     * @return CallbackTransformer
     */
    public function getModelTransformer(ParameterDefinition $definition)
    {
        $parameter = $this->em->getRepository($this->parameterClass)->findOneBy(['path' => $definition->getPath()]);
        $currentValue = $parameter ? $parameter->getValue() : null;

        return new CallbackTransformer(function ($data) {
            return null;
        }, function ($data) use ($currentValue) {
            if (!$data && $currentValue) {
                return $currentValue;
            }

            return $data;
        });
    }
}
