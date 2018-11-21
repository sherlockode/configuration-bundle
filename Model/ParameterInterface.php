<?php

namespace Sherlockode\ConfigurationBundle\Model;

interface ParameterInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getPath();

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path);

    /**
     * Return the value stored in the database.
     * This value is always a string. Use TransformerInterface for non-primary types
     *
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value);
}
