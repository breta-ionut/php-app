<?php

namespace App\HttpKernel;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;

/**
 * The application's controller resolver.
 */
class ControllerResolver extends BaseControllerResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * The controller resolver constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $logger = $container->get('logger', ContainerInterface::NULL_ON_INVALID_REFERENCE);

        parent::__construct($logger);
    }

    /**
     * {@inheritdoc}
     */
    public function getController(Request $request)
    {
        $controller = parent::getController($request);
        if (is_array($controller) && isset($controller[0]) && $controller[0] instanceof ContainerAwareInterface) {
            $controller[0]->setContainer($this->container);
        }

        return $controller;
    }
}
