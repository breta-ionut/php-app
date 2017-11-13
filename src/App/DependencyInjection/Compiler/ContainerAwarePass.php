<?php

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass which injects the container in all container aware services.
 */
class ContainerAwarePass implements CompilerPassInterface
{
    public const CONTAINER_AWARE_TAG = 'kernel.container_aware';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $parameterBag = $container->getParameterBag();
        $containerReference = new Reference('service_container');

        foreach (array_keys($container->findTaggedServiceIds(self::CONTAINER_AWARE_TAG)) as $id) {
            $definition = $container->getDefinition($id);

            // Check that the services implement the container aware interface.
            $class = $parameterBag->resolveValue($definition->getClass());
            $reflection = new \ReflectionClass($class);
            if (!$reflection->implementsInterface(ContainerAwareInterface::class)) {
                throw new LogicException(sprintf(
                    'All container aware services must implement `%s`!',
                    ContainerAwareInterface::class
                ));
            }

            $definition->addMethodCall('setContainer', [$containerReference]);
        }
    }
}
