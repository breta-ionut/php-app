<?php

namespace App\Routing;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

/**
 * The router factory.
 */
class RouterFactory
{
    /**
     * Creates a router instance.
     *
     * @param KernelInterface $kernel
     * @param RequestStack    $requestStack
     * @param string          $rootDir
     * @param string          $cacheDir
     * @param bool            $debug
     *
     * @return Router
     */
    public static function factory(
        KernelInterface $kernel,
        RequestStack $requestStack,
        string $rootDir,
        string $cacheDir,
        bool $debug
    ): Router {
        $requestContext = new RequestContext();
        if (null !== ($request = $requestStack->getMasterRequest())) {
            $requestContext->fromRequest($request);
        }

        $loader = new YamlFileLoader(new FileLocator($kernel, $rootDir.'/../../etc/'));
        $router = new Router($loader, 'routing.yml', ['cache_dir' => $cacheDir, 'debug' => $debug], $requestContext);

        return $router;
    }
}
