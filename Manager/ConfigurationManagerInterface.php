<?php

namespace Sherlockode\ConfigurationBundle\Manager;

interface ConfigurationManagerInterface
{
    /**
     * Get the list of parameters in the configuration
     *
     * @return mixed
     */
    public function getDefinedParameters();
}
