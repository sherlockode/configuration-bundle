<?php

namespace Sherlockode\ConfigurationBundle\Event;

use Symfony\Component\HttpFoundation\Request;

class PreSaveEvent
{
    use ResponseEventTrait;
    use RequestEventTrait;

    private array $parameters;

    public function __construct(Request $request, array $parameters)
    {
        $this->request = $request;
        $this->parameters = $parameters;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
