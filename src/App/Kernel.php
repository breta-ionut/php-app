<?php

namespace App;

use App\DependencyInjection\AssetsExtension;
use App\DependencyInjection\Compiler\ContainerAwarePass;
use App\DependencyInjection\Compiler\Doctrine\RegisterEventListenersAndSubscribersPass;
use App\DependencyInjection\DoctrineExtension;
use App\DependencyInjection\KernelExtension;
use App\DependencyInjection\RoutingExtension;
use App\DependencyInjection\TemplatingExtension;
use App\DependencyInjection\UtilsExtension;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver;
use Symfony\Component\HttpKernel\DependencyInjection\RegisterControllerArgumentLocatorsPass;
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
        $bundlesConfig = require $this->getConfigDir().'/bundles.php';
        $bundles = [];
        foreach ($bundlesConfig as $class => $envs) {
            if (!empty($envs['all']) || !empty($envs[$this->environment])) {
                $bundles[] = new $class();
            }
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $configDir = $this->getConfigDir();

        // Load the application's config files.
        $loader->load($configDir.'/packages/*.yaml', 'glob');
        if (is_dir($configDir.'/packages/'.$this->environment)) {
            $loader->load($configDir.'/packages/'.$this->environment.'/*.yaml', 'glob');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache/'.$this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return $this->getProjectDir().'/var/logs';
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
    protected function getKernelParameters()
    {
        return array_merge(parent::getKernelParameters(), ['kernel.config_dir' => $this->getConfigDir()]);
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
     * {@inheritdoc}
     */
    protected function prepareContainer(ContainerBuilder $container)
    {
        parent::prepareContainer($container);

        $this->addDefaultExtensions($container);
    }

    /**
     * Returns the application's config directory path.
     *
     * @return string
     */
    private function getConfigDir(): string
    {
        return realpath($this->getProjectDir().'/config') ?: $this->getProjectDir().'/config';
    }

    /**
     * Registers the default compiler passes to the container.
     *
     * @param ContainerBuilder $container
     */
    private function addDefaultCompilerPasses(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterListenersPass(EventDispatcher::class))
            ->addCompilerPass(new RegisterControllerArgumentLocatorsPass(ServiceValueResolver::class))
            ->addCompilerPass(new AddConsoleCommandPass())
            ->addCompilerPass(new ContainerAwarePass())
            ->addCompilerPass(new RegisterEventListenersAndSubscribersPass());
    }

    /**
     * Returns the default container extensions to be loaded.
     *
     * @return ExtensionInterface[]
     */
    private function getDefaultExtensions(): array
    {
        return [
            new KernelExtension(),
            new RoutingExtension(),
            new TemplatingExtension(),
            new UtilsExtension(),
            new AssetsExtension(),
            new DoctrineExtension(),
        ];
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
}
