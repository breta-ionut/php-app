<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * The Doctrine container extension.
 */
class DoctrineExtension extends ConfigurableExtension
{
    use ConfigurationAwareExtensionTrait;

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/extensions'));
        $loader->load('doctrine.yml');

        foreach ($configs as $key => $value) {
            $container->setParameter("doctrine.$key", $value);
        }
    }
}
