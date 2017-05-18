<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 18/05/2017
 * Time: 12:10
 */

namespace Beyerz\GoogleApiBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class GoogleServicePass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        //get all the defined services
        $parameters = $container->getParameterBag()->all();
        var_dump($parameters);
        die;

//        if (!$container->hasDefinition('beyerz_google_api.service_provider')) {
//            throw new \Exception('beyerz_google_api.service_provider required to setup google services');
//        }
//
//        $gmailServiceDefinition = new Definition(\Google_Service_Gmail::class, [new Reference("beyerz_google_api.google_client")]);
//        $container->setDefinition('beyerz_google_api.sub_service.gmail', $gmailServiceDefinition);
//
//        $gmailServiceProviderDefinition = $container->getDefinition('beyerz_google_api.service_provider');
//        $gmailServiceProviderDefinition->replaceArgument(1, new Reference('beyerz_google_api.sub_service.gmail'));
//        $container->setDefinition('beyerz_google_api.service.gmail', $gmailServiceProviderDefinition);
    }
}