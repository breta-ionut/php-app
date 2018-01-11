<?php

namespace App\DependencyInjection\Compiler\Doctrine;

use App\Doctrine\EventManager;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\DependencyInjection\Argument\ServiceClosureArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Compiler pass which injects Doctrine event listeners and subscribers into the Doctrine event manager.
 */
class RegisterEventListenersAndSubscribersPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public const SUBSCRIBER_TAG = 'doctrine.event_subscriber';
    private const LISTENER_TAG = 'doctrine.event_listener';

    private const EVENT_MANAGER_ID = EventManager::class;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $eventManager = $container->getDefinition(self::EVENT_MANAGER_ID);
        $parameterBag = $container->getParameterBag();

        // Inject the subscribers into the event manager.
        foreach ($this->findAndSortTaggedServices(self::SUBSCRIBER_TAG, $container) as $subscriber) {
            $class = $parameterBag->resolveValue($container->getDefinition((string) $subscriber)->getClass());
            $reflection = new \ReflectionClass($class);
            if (!$reflection->implementsInterface(EventSubscriber::class)) {
                throw new LogicException(sprintf(
                    'All Doctrine event subscribers must implement `%s`!',
                    EventSubscriber::class
                ));
            }

            $eventManager->addMethodCall('addEventSubscriber', [$subscriber]);
        }

        // Collect the listeners.
        $listeners = [];
        foreach ($container->findTaggedServiceIds(self::LISTENER_TAG) as $listenerId => $tags) {
            if (!isset($tags[0]['event'])) {
                throw new LogicException(sprintf(
                    'No event(s) specified for Doctrine event listener with id `%s`!',
                    $listenerId
                ));
            }

            $listeners[$tags[0]['priority'] ?? 0][$listenerId] = (array) $tags[0]['event'];
        }

        if (!$listeners) {
            return;
        }

        // Sort the listeners by priority.
        krsort($listeners);
        $listeners = call_user_func_array('array_merge', $listeners);

        // Inject the listeners into the event manager.
        foreach ($listeners as $listenerId => $events) {
            // The listeners are always lazy-loaded.
            $eventManager->addMethodCall(
                'addEventListener',
                [$events, new ServiceClosureArgument(new Reference($listenerId))]
            );
        }
    }
}
