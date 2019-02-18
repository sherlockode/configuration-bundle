<?php

namespace Sherlockode\ConfigurationBundle\Transformer;

class BooleanTransformer implements TransformerInterface
{
    public function transform($data)
    {
        return (bool)$data;
    }

    public function reverseTransform($data)
    {
        return $data ? 1 : 0;
    }
}
