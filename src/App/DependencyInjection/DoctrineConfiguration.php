<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The Doctrine configuration definition.
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
                    ->defaultValue(3306)
                ->end()

                ->scalarNode('user')
                    ->cannotBeEmpty()
                    ->defaultValue('root')
                ->end()

                ->scalarNode('password')
                    ->defaultValue('')
                ->end()

                ->scalarNode('database')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
