<?php

namespace App\DependencyInjection;

use App\DependencyInjection\Compiler\ContainerAwarePass;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * The kernel container extension.
 */
class KernelExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('kernel.yaml');

        // Tag all services implementing the container aware interface with the container aware tag.
        $container->registerForAutoconfiguration(ContainerAwareInterface::class)
            ->addTag(ContainerAwarePass::CONTAINER_AWARE_TAG);
        // Register all commands as services.
        $container->registerForAutoconfiguration(Command::class)
            ->addTag('console.command');
    }
}
