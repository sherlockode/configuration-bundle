<?php

namespace Sherlockode\ConfigurationBundle\Event;

use Symfony\Component\HttpFoundation\Request;

trait RequestEventTrait
{
    private ?Request $request = null;

    public function getRequest(): ?Request
    {
        return $this->request;
    }
}
