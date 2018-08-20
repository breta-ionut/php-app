<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * The application's abstract controller.
 */
abstract class AbstractController implements ServiceSubscriberInterface
{
    /**
     * The controller's service locator.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedServices()
    {
        return [
            EntityManagerInterface::class,
            EngineInterface::class,
        ];
    }

    /**
     * The controller constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Fetches the service with the given id from the locator.
     *
     * @param string $id
     *
     * @return object|null
     */
    public function get(string $id): ?object
    {
        return $this->container->get($id);
    }

    /**
     * Renders the given template using the given parameters and returns a response containing the result.
     *
     * @param string        $template
     * @param array         $parameters
     * @param Response|null $response
     *
     * @return Response
     */
    protected function render(string $template, array $parameters = [], Response $response = null): Response
    {
        if (null === $response) {
            $response = new Response();
        }

        $content = $this->get(EngineInterface::class)->render($template, $parameters);
        $response->setContent($content);

        return $response;
    }
}
