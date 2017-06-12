<?php

namespace App\Routing;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

/**
 * Matches defined application routes with user requests.
 */
class RouterListener
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * The listener constructor.
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Listens to the 'kernel.request' event and matches defined routes.
     *
     * @param GetResponseEvent $event
     */
    public function onRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $request->attributes->add($this->router->match($request->getPathInfo()));
    }
}
