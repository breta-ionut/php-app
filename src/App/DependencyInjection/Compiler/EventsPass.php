<?php

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Collects and registers event listeners and subscribers throughout the app.
 */
class EventsPass implements CompilerPassInterface
{
    private const EVENT_DISPATCHER_ID = 'event_dispatcher';

    private const TAG_LISTENER = 'kernel.event_listener';
    private const TAG_SUBSCRIBER = 'kernel.event_subscriber';

    /**
     * {@inheritdoc}
     *
     * @throws LogicException If listeners or subscribers were declared incorrectly.
     */
    public function process(ContainerBuilder $container)
    {
        $eventDispatcher = $container->getDefinition(self::EVENT_DISPATCHER_ID);
        $parameterBag = $container->getParameterBag();

        // Find and register event listeners.
        foreach ($container->findTaggedServiceIds(self::TAG_LISTENER) as $listenerId => $tags) {
            foreach ($tags as $tag) {
                if (!isset($tag['event'])) {
                    throw new LogicException(sprintf('No event specified for listener `%s`.', $listenerId));
                }

                $listenerClass = $parameterBag->resolveValue($container->getDefinition($listenerId)->getClass());
                if (isset($tag['method']) && method_exists($listenerClass, $tag['method'])) {
                    $listener = [new Reference($listenerId), $tag['method']];
                } elseif (method_exists($listenerClass, '__invoke')) {
                    $listener = new Reference($listenerId);
                } else {
                    throw new LogicException(sprintf(
                        'The listener `%s` of event `%s` must either be invokable or you must specify a method to be '
                        .'called when dispatching the event.',
                        $listenerId,
                        $tag['event']
                    ));
                }

                $eventDispatcher->addMethodCall('addListener', [$tag['event'], $listener, $tag['priority'] ?? 0]);
            }
        }

        // Find and register event subscribers.
        foreach ($container->findTaggedServiceIds(self::TAG_SUBSCRIBER) as $subscriberId => $tags) {
            $subscriberClass = $parameterBag->resolveValue($container->getDefinition($subscriberId)->getClass());
            $reflection = new \ReflectionClass($subscriberClass);
            if (!$reflection->implementsInterface(EventSubscriberInterface::class)) {
                throw new LogicException(sprintf(
                    'The class of subscriber `%s`(%s) doesn\'t implement interface `%s`.',
                    $subscriberId,
                    $subscriberClass,
                    EventSubscriberInterface::class
                ));
            }

            $eventDispatcher->addMethodCall('addSubscriber', [new Reference($subscriberId)]);
        }
    }
}
