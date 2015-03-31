<?php

namespace AdrienSamson\GuzzleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('adriensamson_guzzle');

        $rootNode->children()
            ->arrayNode('clients')
                ->prototype('array')
                    ->children()
                        ->variableNode('config')
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
