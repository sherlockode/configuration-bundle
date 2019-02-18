<?php

namespace Sherlockode\ConfigurationBundle\Transformer;

class ArrayTransformer implements TransformerInterface
{
    public function transform($data)
    {
        if (!$data) {
            return null;
        }
        if (false !== ($unserialized = @unserialize($data))) {
            return $unserialized;
        }

        return null;
    }

    public function reverseTransform($data)
    {
        if (is_array($data)) {
            return serialize($data);
        }

        return $data;
    }
}
