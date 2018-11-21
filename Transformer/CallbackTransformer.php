<?php

namespace Sherlockode\ConfigurationBundle\Transformer;

class CallbackTransformer implements TransformerInterface
{
    /**
     * @var callable
     */
    private $transform;

    /**
     * @var callable
     */
    private $reverseTransform;

    /**
     * @param callable $transform
     * @param callable $reverseTransform
     */
    public function __construct($transform, $reverseTransform)
    {
        $this->transform = $transform;
        $this->reverseTransform = $reverseTransform;
    }

    public function transform($data)
    {
        return \call_user_func($this->transform, $data);
    }

    public function reverseTransform($data)
    {
        return \call_user_func($this->reverseTransform, $data);
    }
}
