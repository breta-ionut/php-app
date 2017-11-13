<?php

namespace App\DependencyInjection;

use App\DependencyInjection\Compiler\ContainerAwarePass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * The application container extension.
 */
class ApplicationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('application.yml');

        // Tag all services implementing the container aware interface with the container aware tag.
        $container->registerForAutoconfiguration(ContainerAwareInterface::class)
            ->addTag(ContainerAwarePass::CONTAINER_AWARE_TAG);
    }
}
