<?php

namespace App\Controller\ArgumentResolver;

use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\SessionValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;

/**
 * The application's argument resolver factory.
 */
class ArgumentResolverFactory
{
    /**
     * Creates an argument resolver.
     *
     * @param ServiceValueResolver $serviceValueResolver
     *
     * @return ArgumentResolverInterface
     */
    public static function factory(ServiceValueResolver $serviceValueResolver): ArgumentResolverInterface
    {
        return new ArgumentResolver(null, [
            new RequestAttributeValueResolver(),
            new RequestValueResolver(),
            new SessionValueResolver(),
            new DefaultValueResolver(),
            new VariadicValueResolver(),
            $serviceValueResolver,
        ]);
    }
}
