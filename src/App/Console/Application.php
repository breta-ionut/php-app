<?php

namespace App\Console;

use App\Command\Doctrine\SchemaDropCommand;
use App\Command\Doctrine\SchemaUpdateCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * The application's console entry point.
 */
class Application extends BaseApplication
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * If the bundle commands were registered.
     *
     * @var bool
     */
    private $commandsRegistered = false;

    /**
     * The console application constructor.
     *
     * @param KernelInterface $kernel
     * @param string          $version
     */
    public function __construct(KernelInterface $kernel, string $version)
    {
        $this->kernel = $kernel;

        parent::__construct($kernel->getName(), $version);
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->kernel->boot();
        $container = $this->kernel->getContainer();

        // Register the container to the container aware commands.
        foreach ($this->all() as $command) {
            if ($command instanceof ContainerAwareInterface) {
                $command->setContainer($container);
            }
        }

        // Register the event dispatcher.
        $this->setDispatcher($container->get('event_dispatcher'));

        return parent::doRun($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        $this->registerCommands();

        return parent::has($name);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        $this->registerCommands();

        return parent::get($name);
    }

    /**
     * {@inheritdoc}
     */
    public function find($name)
    {
        $this->registerCommands();

        return parent::find($name);
    }

    /**
     * {@inheritdoc}
     */
    public function all($namespace = null)
    {
        $this->registerCommands();

        return parent::all($namespace);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), [
            new SchemaDropCommand(),
            new SchemaUpdateCommand(),
        ]);
    }

    /**
     * Registers commands from the kernel's bundles.
     */
    private function registerCommands()
    {
        if ($this->commandsRegistered) {
            return;
        }

        // Ensure the kernel is booted.
        $this->kernel->boot();

        foreach ($this->kernel->getBundles() as $bundle) {
            if ($bundle instanceof Bundle) {
                $bundle->registerCommands($this);
            }
        }

        $this->commandsRegistered = true;
    }
}
