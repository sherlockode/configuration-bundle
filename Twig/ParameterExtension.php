<?php

namespace Sherlockode\ConfigurationBundle\Twig;

use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ParameterExtension extends AbstractExtension
{
    /**
     * @var ParameterManagerInterface
     */
    private $parameterManager;

    public function __construct(ParameterManagerInterface $parameterManager)
    {
        $this->parameterManager = $parameterManager;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('configuration_parameter', [$this, 'getParameterValue']),
        ];
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParameterValue(string $key, $default = null)
    {
        return $this->parameterManager->get($key, $default);
    }
}
