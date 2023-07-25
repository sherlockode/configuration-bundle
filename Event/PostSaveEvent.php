<?php

namespace Sherlockode\ConfigurationBundle\Event;

use Symfony\Component\HttpFoundation\Request;

class PostSaveEvent
{
    use ResponseEventTrait;
    use RequestEventTrait;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
