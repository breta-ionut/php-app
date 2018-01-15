<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * The assets configuration.
 */
class AssetsConfiguration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('assets');

        $rootNode
            ->children()
                ->scalarNode('base_path')
                    ->defaultValue('')
                ->end()

                ->scalarNode('version_format')
                    ->cannotBeEmpty()
                    ->defaultValue('%%s?%%s')
                ->end()

                ->scalarNode('version')->end()
            ->end();

        return $treeBuilder;
    }
}
