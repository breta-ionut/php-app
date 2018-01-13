<?php

namespace App\DependencyInjection;

use App\DependencyInjection\Compiler\Doctrine\RegisterEventListenersAndSubscribersPass;
use Doctrine\Common\EventSubscriber;
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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('doctrine.yaml');

        foreach ($configs as $key => $value) {
            $container->setParameter("doctrine.$key", $value);
        }

        // Tag all services implementing the Doctrine event subscriber interface with the event subscriber tag.
        $container->registerForAutoconfiguration(EventSubscriber::class)
            ->addTag(RegisterEventListenersAndSubscribersPass::SUBSCRIBER_TAG);
    }
}
