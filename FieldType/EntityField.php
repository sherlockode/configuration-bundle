<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Doctrine\Common\Persistence\ObjectManager;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntityField extends AbstractField
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * @return string
     */
    public function getFormType()
    {
        return EntityType::class;
    }

    public function getFormOptions(ParameterDefinition $definition)
    {
        return [
            'class' => $definition->getOption('class'),
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

            return $this->om->getRepository($class)->find($data);
        }, function ($data) use ($class) {
            if (!$data) {
                return null;
            }
            $metadata = $this->om->getClassMetadata($class);
            $id = $metadata->getIdentifierValues($data);

            return reset($id);
        });
    }
}
