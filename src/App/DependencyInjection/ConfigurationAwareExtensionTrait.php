<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Extension\ConfigurationExtensionInterface;

/**
 * Trait to be used by configuration aware internal extensions.
 */
trait ConfigurationAwareExtensionTrait
{
    /**
     * Provides an implementation for the ConfigurationExtensionInterface::getConfiguration method specific to internal
     * extensions.
     *
     * @param array            $config
     * @param ContainerBuilder $container
     *
     * @return ConfigurationInterface
     *
     * @throws BadMethodCallException If naming conventions aren't followed and therefore the configuration class cannot
     *                                be detected.
     *
     * @see ConfigurationExtensionInterface
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        $className = get_class($this);
        if (substr($className, -9) !== 'Extension') {
            throw new BadMethodCallException(
                'The class of the current extension doesn\'t follow the standard naming convention. You should provide '
                .'your own implementation for this method and not use this trait.'
            );
        }

        $configClassName = substr($className, 0, -9).'Configuration';
        if (!class_exists($configClassName)) {
            throw new BadMethodCallException(
                'No configuration class detected. It is either missing or it follows a different naming convention.'
            );
        }

        return new $configClassName();
    }
}
