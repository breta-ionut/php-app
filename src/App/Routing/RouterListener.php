<?php

namespace App\Routing;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;

/**
 * Matches defined application routes with user requests.
 */
class RouterListener
{
    /**
     * @var RequestMatcherInterface
     */
    private $requestMatcher;

    /**
     * The listener constructor.
     *
     * @param RequestMatcherInterface $requestMatcher
     */
    public function __construct(RequestMatcherInterface $requestMatcher)
    {
        $this->requestMatcher = $requestMatcher;
    }

    /**
     * Listens to the 'kernel.request' event and matches defined routes.
     *
     * @param GetResponseEvent $event
     */
    public function onRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $request->attributes->add($this->requestMatcher->matchRequest($request));
    }
}
