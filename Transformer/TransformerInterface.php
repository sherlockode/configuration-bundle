<?php

namespace Sherlockode\ConfigurationBundle\Transformer;

interface TransformerInterface
{
    /**
     * Transform the database value (string) into the expected parameter type
     *
     * @param string $data
     *
     * @return mixed
     */
    public function transform($data);

    /**
     * Transform back the parameter value into the database value (string)
     *
     * @param mixed $data
     *
     * @return string
     */
    public function reverseTransform($data);
}
