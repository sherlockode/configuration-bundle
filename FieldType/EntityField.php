<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntityField extends AbstractField
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return string
     */
    public function getFormType(ParameterDefinition $definition)
    {
        return EntityType::class;
    }

    public function getFormOptions(ParameterDefinition $definition)
    {
        return [
            'class' => $definition->getOption('class'),
            'placeholder' => $definition->getOption('placeholder', null),
        ];
    }

    public function getName()
    {
        return 'entity';
    }

    public function getModelTransformer(ParameterDefinition $definition)
    {
        $class = $definition->getOption('class');
        return new CallbackTransformer(function ($data) use ($class) {
            if (!$data) {
                return null;
            }

            return $this->em->getRepository($class)->find($data);
        }, function ($data) use ($class) {
            if (!$data) {
                return null;
            }
            $metadata = $this->em->getClassMetadata($class);
            $id = $metadata->getIdentifierValues($data);

            return reset($id);
        });
    }
}
