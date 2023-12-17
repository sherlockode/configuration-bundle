<?php

namespace Sherlockode\ConfigurationBundle\Event;

use Symfony\Component\HttpFoundation\Request;

trait RequestEventTrait
{
    private Request $request;

    public function getRequest(): Request
    {
        return $this->request;
    }
}
