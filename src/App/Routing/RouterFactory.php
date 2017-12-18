<?php

namespace App\Routing;

use App\Routing\Loader\YamlFilesLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Router;

/**
 * The router factory.
 */
class RouterFactory
{
    private const BUNDLE_ROUTING_CONFIG_PATH = 'Resources/config/routing.yaml';
    private const APP_ROUTING_CONFIG_PATH = 'routing.yaml';

    /**
     * Creates a router instance.
     *
     * @param array        $bundles      The bundles metadata.
     * @param string       $configDir
     * @param string       $cacheDir
     * @param bool         $debug
     * @param RequestStack $requestStack
     *
     * @return Router
     */
    public static function factory(
        array $bundles,
        string $configDir,
        string $cacheDir,
        bool $debug,
        RequestStack $requestStack
    ): Router {
        $loader = new YamlFilesLoader(new FileLocator());

        // Get the routing configs from the bundles.
        $routingConfigs = [];
        foreach ($bundles as $bundle) {
            $routingConfig = $bundle['path'].'/'.self::BUNDLE_ROUTING_CONFIG_PATH;
            if (file_exists($routingConfig)) {
                $routingConfigs[] = $routingConfig;
            }
        }

        // Append the global routing config file.
        $routingConfigs[] = $configDir.'/'.self::APP_ROUTING_CONFIG_PATH;

        // Build the request context.
        $requestContext = new RequestContext();
        if (null !== ($request = $requestStack->getMasterRequest())) {
            $requestContext->fromRequest($request);
        }

        return new Router($loader, $routingConfigs, ['cache_dir' => $cacheDir, 'debug' => $debug], $requestContext);
    }
}
