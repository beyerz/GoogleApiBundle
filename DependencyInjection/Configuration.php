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
    const APPLICATION_NAME = 'application_name';
    const CREDENTIALS_MANAGER = 'credentials_manager';
    const CLIENT_SECRET_PATH = 'client_secret_path';
    const SERVICES = 'services';
    const SERVICE_GMAIL = 'gmail';
    const ACCESS_TYPE = 'access_type';
    const SCOPES = 'scopes';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('beyerz_google_api');

        $rootNode->children()
            ->scalarNode(self::APPLICATION_NAME)->end()
            ->scalarNode(self::CREDENTIALS_MANAGER)->end()
            ->scalarNode(self::CLIENT_SECRET_PATH)->end()
            ->arrayNode(self::SCOPES)
                ->prototype('scalar')->end()
            ->end()
            ->arrayNode(self::SERVICES)
                ->prototype('array')->children()
                    ->scalarNode(self::ACCESS_TYPE)->defaultValue('online')->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
