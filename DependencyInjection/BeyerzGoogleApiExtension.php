<?php

namespace Beyerz\GoogleApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BeyerzGoogleApiExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->exposeConfigToParameters($config, $container);
//        var_dump($config);die;

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    private function exposeConfigToParameters(array $config, ContainerBuilder $container)
    {
        $base = 'beyerz_google_api';
        if (isset($config['application_name'])) {
            $container->setParameter(sprintf('%s.%s', $base, 'application_name'), $config['application_name']);
        }
        if (isset($config['client_secret_path'])) {
            $container->setParameter(sprintf('%s.%s', $base, 'client_secret_path'), ltrim($config['client_secret_path'],"/"));
        }

        if (isset($config['services']['gmail']['scopes'])) {
            $container->setParameter(sprintf('%s.%s.%s.%s', $base, 'service', 'gmail', 'scopes'), implode(" ", $config['services']['gmail']['scopes']));
        }
    }
}
