<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * The Doctrine container extension. Integrates the Doctrine DBAL component into the application.
 */
class DoctrineExtension extends ConfigurableExtension
{
    use ConfigurationAwareExtensionTrait;

    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/extensions'));
        $loader->load('doctrine.yml');

        foreach ($mergedConfig as $configKey => $configValue) {
            $container->setParameter('doctrine.'.$configKey, $configValue);
        }
    }
}
