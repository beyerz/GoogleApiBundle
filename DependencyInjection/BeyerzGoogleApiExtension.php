<?php

namespace Beyerz\GoogleApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BeyerzGoogleApiExtension extends Extension
{
    const TAG_CONTAINER_AWARE = 'beyerz_google_api.container_aware';
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
            $credentialsManagerClass = $config[Configuration::CREDENTIALS_MANAGER];
            if (!class_exists($credentialsManagerClass)) {
                throw new \Exception("Defined class in config for credential manager does not exist");
            }
            $credentialsManager = new Definition($credentialsManagerClass);

            $uses = class_uses($credentialsManagerClass);
            if (array_key_exists(ContainerAwareTrait::class, $uses)) {
                $credentialsManager->addTag(self::TAG_CONTAINER_AWARE);
            }

            $container->setDefinition('beyerz_google_api.credentials_manager',$credentialsManager);
        }

        if (isset($config[Configuration::CLIENT_SECRET_PATH])) {
            $container->setParameter(sprintf('%s.%s', $base, Configuration::CLIENT_SECRET_PATH), ltrim($config[Configuration::CLIENT_SECRET_PATH], "/"));
        }

        if (isset($config[Configuration::SCOPES])) {
            $container->setParameter(sprintf('%s.%s', $base,Configuration::SCOPES), $config[Configuration::SCOPES]);
        }

        $services = [];
        foreach ($config[Configuration::SERVICES] as $serviceName => $serviceConfig) {
            $container->setParameter(sprintf('%s.%s.%s.%s', $base, Configuration::SERVICES, $serviceName, Configuration::ACCESS_TYPE), $serviceConfig[Configuration::ACCESS_TYPE]);
            array_push($services, $serviceName);
        }
        
        if (!empty($services)) {
            $container->setParameter(sprintf('%s.%s', $base, Configuration::SERVICES), $services);
        }
    }

}
