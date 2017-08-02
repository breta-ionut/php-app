<?php

namespace App;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * The application kernel.
 */
class Kernel extends BaseKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return require $this->rootDir.'/../../etc/bundles.php';
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        // Load the internal services config file.
        $loader->load($this->rootDir.'/Resources/config/services.yml');

        // Load the application config file.
        $loader->load($this->rootDir.'/../../etc/config_'.$this->environment.'.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return $this->rootDir.'/../../var/cache/'.$this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->rootDir.'/../../var/logs';
    }

    /**
     * {@inheritdoc}
     */
    protected function getHttpKernel()
    {
        return $this->container->get(HttpKernel::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function initializeContainer()
    {
        $dotenv = new Dotenv();
        $dotenv->load($this->rootDir.'/../../etc/parameters.env');

        parent::initializeContainer();
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareContainer(ContainerBuilder $container)
    {
        parent::prepareContainer($container);

        $this->addDefaultExtensions($container);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildContainer()
    {
        $container = parent::buildContainer();

        $this->addDefaultCompilerPasses($container);

        return $container;
    }

    /**
     * Returns the default container extensions to be loaded.
     *
     * @return ExtensionInterface[]
     */
    private function getDefaultExtensions(): array
    {
        return [];
    }

    /**
     * Registers the default extensions to the container.
     *
     * @param ContainerBuilder $container
     */
    private function addDefaultExtensions(ContainerBuilder $container)
    {
        foreach ($this->getDefaultExtensions() as $extension) {
            $container->registerExtension($extension);

            $alias = $extension->getAlias();
            if (!$container->getExtensionConfig($alias)) {
                $container->loadFromExtension($alias, []);
            }
        }
    }

    /**
     * Registers the default compiler passes to the container.
     *
     * @param ContainerBuilder $container
     */
    private function addDefaultCompilerPasses(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterListenersPass());
    }
}
