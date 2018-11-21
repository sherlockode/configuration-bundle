<?php

namespace Sherlockode\ConfigurationBundle\Form\Type;

use Sherlockode\ConfigurationBundle\Manager\ParameterManager;
use Sherlockode\ConfigurationBundle\Model\ParameterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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

    public function __construct(ParameterManager $parameterManager)
    {
        $this->parameterManager = $parameterManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->parameterManager->getConfiguration() as $path => $config) {
            $builder->add($path, ParameterType::class, [
                'label' => $config['label'],
                'parameter_field_type' => $config['type'],
            ]);
        }

        $builder->addViewTransformer(new CallbackTransformer(function ($data) {
            $newData = [];
            /** @var ParameterInterface $param */
            foreach ($data as $param) {
                $newData[$param->getPath()] = $param;
            }
            return $newData;
        }, function ($data) use ($builder) {
            /** @var ParameterInterface $param */
            foreach ($data as $name => $param) {
                $param->setPath($name);
            }
            return $data;
        }));
    }
}
