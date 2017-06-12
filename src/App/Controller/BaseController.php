<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * The application's base controller.
 */
abstract class BaseController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Returns the service with the given id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function get(string $id)
    {
        return $this->container->get($id);
    }

    /**
     * Renders the given template with the given parameters.
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

        $content = $this->get('templating')->render($template, $parameters);
        $response->setContent($content);

        return $response;
    }
}
