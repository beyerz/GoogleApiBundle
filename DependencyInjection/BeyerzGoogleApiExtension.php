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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    private function exposeConfigToParameters(array $config, ContainerBuilder $container)
    {
        $base = 'beyerz_google_api';
        if (isset($config[Configuration::APPLICATION_NAME])) {
            $container->setParameter(sprintf('%s.%s', $base, Configuration::APPLICATION_NAME), $config[Configuration::APPLICATION_NAME]);
        }

        if (isset($config[Configuration::CREDENTIALS_MANAGER])) {
            if (!class_exists($config[Configuration::CREDENTIALS_MANAGER])) {
                throw new \Exception("Defined class in config for credential manager does not exist");
            }
            $container->setParameter(sprintf('%s.%s.class', $base, Configuration::CREDENTIALS_MANAGER), $config[Configuration::CREDENTIALS_MANAGER]);
        }

        if (isset($config[Configuration::CLIENT_SECRET_PATH])) {
            $container->setParameter(sprintf('%s.%s', $base, Configuration::CLIENT_SECRET_PATH), ltrim($config[Configuration::CLIENT_SECRET_PATH], "/"));
        }

        foreach ($config[Configuration::SERVICES] as $serviceName => $serviceConfig) {
            $container->setParameter(sprintf('%s.%s.%s.%s', $base, Configuration::SERVICES, $serviceName, Configuration::ACCESS_TYPE), $serviceConfig[Configuration::ACCESS_TYPE]);
            $container->setParameter(sprintf('%s.%s.%s.%s', $base, Configuration::SERVICES, $serviceName, Configuration::SCOPES), $serviceConfig[Configuration::SCOPES]);
        }
    }

}
