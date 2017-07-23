<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 18/05/2017
 * Time: 12:10
 */

namespace Beyerz\GoogleApiBundle\DependencyInjection\Compiler;


use Beyerz\GoogleApiBundle\Cache\NullCacheItemPool;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class GoogleClientPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('beyerz_google_api.google_client')) {
            throw new \Exception('beyerz_google_api.google_client service not found, so google client could not be created');
        }

        //get required client config
        $config = [
            'application_name'       => $container->getParameter('beyerz_google_api.application_name'),
            'include_granted_scopes' => true,
        ];
        $definition = $container->getDefinition('beyerz_google_api.google_client');
        $definition->addArgument($config);
        $definition->addMethodCall('addScope', $container->getParameter('beyerz_google_api.scopes'));
        $definition->addMethodCall('setCache', [new Definition(NullCacheItemPool::class)]);
        $definition->addMethodCall('setAuthConfig', [sprintf('%s/%s', $container->getParameter('kernel.root_dir'), $container->getParameter('beyerz_google_api.client_secret_path'))]);
    }
}