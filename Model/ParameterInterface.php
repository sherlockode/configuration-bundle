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
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value);
}
