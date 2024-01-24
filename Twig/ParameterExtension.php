<?php

namespace Sherlockode\ConfigurationBundle\Twig;

use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ParameterExtension extends AbstractExtension
{
    private ParameterManagerInterface $parameterManager;

    public function __construct(ParameterManagerInterface $parameterManager)
    {
        $this->parameterManager = $parameterManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('configuration_parameter', [$this, 'getParameterValue']),
        ];
    }

    public function getParameterValue(string $key, mixed $default = null): mixed
    {
        return $this->parameterManager->get($key, $default);
    }
}
