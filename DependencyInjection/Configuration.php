<?php

namespace Beyerz\GoogleApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('beyerz_google_api');

        $rootNode->children()
            ->scalarNode('application_name')->end()
            ->scalarNode('credentials_manager')->end()
            ->scalarNode('client_secret_path')->end()
            ->arrayNode('services')
                ->prototype('array')
                    ->children()
                        ->arrayNode('scopes')->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
