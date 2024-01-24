<?php

namespace Sherlockode\ConfigurationBundle\FieldType;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\ConfigurationBundle\Parameter\ParameterDefinition;
use Sherlockode\ConfigurationBundle\Transformer\CallbackTransformer;
use Sherlockode\ConfigurationBundle\Transformer\TransformerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntityField extends AbstractField
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFormType(ParameterDefinition $definition): string
    {
        return EntityType::class;
    }

    public function getFormOptions(ParameterDefinition $definition): array
    {
        $options = [
            'class' => $definition->getOption('class'),
            'placeholder' => $definition->getOption('placeholder', null),
            'multiple' => $definition->getOption('multiple', null),
        ];

        $label = $definition->getOption('choice_label', null);
        // only set the choice_label if defined to keep the Form native behavior
        if ($label) {
            $options['choice_label'] = $label;
        }

        return $options;
    }

    public function getName(): string
    {
        return 'entity';
    }

    public function getModelTransformer(ParameterDefinition $definition): ?TransformerInterface
    {
        $class = $definition->getOption('class');
        $multiple = $definition->getOption('multiple');

        return new CallbackTransformer(function ($data) use ($class, $multiple) {
            if (!$data) {
                return null;
            }

            if ($multiple) {
                $data = explode(',', $data);
                $metadata = $this->em->getClassMetadata($class);
                $idField = $metadata->getSingleIdentifierFieldName();
                $result = $this->em->getRepository($class)->findBy([$idField => $data]);
            } else {
                $result = $this->em->getRepository($class)->find($data);
            }

            return $result;
        }, function ($data) use ($class, $multiple) {
            if (!$data) {
                return null;
            }
            $metadata = $this->em->getClassMetadata($class);

            if ($multiple) {
                $result = [];
                foreach ($data as $entity) {
                    $id = $metadata->getIdentifierValues($entity);
                    $result[] = reset($id);
                }
                $result = implode(',', $result);
            } else {
                $id = $metadata->getIdentifierValues($data);
                $result = reset($id);
            }

            return $result;
        });
    }
}
