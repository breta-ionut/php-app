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

                ->scalarNode('url')
                    ->cannotBeEmpty()
                ->end()

                ->scalarNode('charset')
                    ->cannotBeEmpty()
                    ->defaultValue('utf8mb4')
                ->end()

                ->scalarNode('collate')
                    ->cannotBeEmpty()
                    ->defaultValue('utf8mb4_unicode_ci')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
