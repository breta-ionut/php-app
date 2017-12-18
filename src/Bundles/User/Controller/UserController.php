<?php

namespace Bundles\User\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * The user controller.
 */
class UserController
{
    /**
     * The login page.
     *
     * @return Response
     */
    public function loginAction(): Response
    {
        return new Response('Hi!');
    }
}
