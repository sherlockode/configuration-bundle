<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ConstraintManagerInterface
{
    /**
     * @param array $definition
     *
     * @return array
     */
    public function getConstraints(array $definition): array;
}
