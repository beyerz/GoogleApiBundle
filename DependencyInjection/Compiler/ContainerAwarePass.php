<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 18/05/2017
 * Time: 12:10
 */

namespace Beyerz\GoogleApiBundle\DependencyInjection\Compiler;


use Beyerz\GoogleApiBundle\DependencyInjection\BeyerzGoogleApiExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ContainerAwarePass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        if(!$container->has('service_container')){
            return;
        }

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds(BeyerzGoogleApiExtension::TAG_CONTAINER_AWARE);

        foreach ($taggedServices as $id => $tags) {
            $service = $container->getDefinition($id);
            $service->addMethodCall('setContainer',[new Reference('service_container')]);
        }
    }
}