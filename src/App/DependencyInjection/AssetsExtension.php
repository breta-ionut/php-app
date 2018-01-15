<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * The assets container extension.
 */
class AssetsExtension extends ConfigurableExtension
{
    use ConfigurationAwareExtensionTrait;

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('assets.yaml');

        foreach ($mergedConfig as $key => $value) {
            $container->setParameter("assets.$key", $value);
        }
    }
}
