<?php

namespace Sherlockode\ConfigurationBundle\Event;

use Symfony\Component\HttpFoundation\Request;

trait RequestEventTrait
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
