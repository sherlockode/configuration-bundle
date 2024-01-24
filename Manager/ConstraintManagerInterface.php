<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ConstraintManagerInterface
{
    public function getConstraints(array $definition): array;
}
