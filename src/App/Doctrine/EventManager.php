<?php

namespace App\Doctrine;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventManager as BaseEventManager;

/**
 * Doctrine event manager which allows the registering of lazy-loading listeners.
 */
class EventManager extends BaseEventManager
{
    /**
     * The registered listeners.
     *
     * @var array The keys are event names and the values are arrays containing the listeners for these events.
     */
    private $listeners = [];

    /**
     * Keeps track of which events had their listeners initialized.
     *
     * @var array
     */
    private $initialized = [];

    /**
     * {@inheritdoc}
     */
    public function dispatchEvent($eventName, EventArgs $eventArgs = null)
    {
        if (!isset($this->listeners[$eventName])) {
            return;
        }

        $eventArgs = $eventArgs ?: EventArgs::getEmptyInstance();

        $initialized = isset($this->initialized[$eventName]);
        foreach ($this->listeners[$eventName] as $hash => $listener) {
            // Lazy-load the listeners for this event if not already loaded.
            if (!$initialized && $listener instanceof \Closure) {
                $this->listeners[$eventName][$hash] = $listener = $listener();
            }

            $listener->$eventName($eventArgs);
        }
        // Mark the listeners for this event as loaded.
        $this->initialized[$eventName] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($event = null)
    {
        return $event ? $this->listeners[$event] : $this->listeners;
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($event)
    {
        return !empty($this->listeners[$event]);
    }

    /**
     * {@inheritdoc}
     */
    public function addEventListener($events, $listener)
    {
        $hash = spl_object_hash($listener);
        foreach ((array) $events as $event) {
            $this->listeners[$event][$hash] = $listener;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeEventListener($events, $listener)
    {
        $hash = spl_object_hash($listener);
        foreach ((array) $events as $event) {
            unset($this->listeners[$event][$hash]);
        }
    }
}
