<?php

namespace Sherlockode\ConfigurationBundle\Event;

use Symfony\Component\HttpFoundation\Response;

trait ResponseEventTrait
{
    private Response $response;

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }
}
