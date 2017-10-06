<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The Doctrine configuration.
 */
class DoctrineConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('doctrine');

        $rootNode
            ->children()
                ->scalarNode('driver')
                    ->cannotBeEmpty()
                ->end()

                ->scalarNode('host')
                    ->cannotBeEmpty()
                    ->defaultValue('localhost')
                ->end()

                ->scalarNode('port')
                    ->cannotBeEmpty()
                    ->defaultValue(3306)
                ->end()

                ->scalarNode('user')
                    ->cannotBeEmpty()
                ->end()

                ->scalarNode('password')
                    ->cannotBeEmpty()
                ->end()

                ->scalarNode('database')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
