<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 18/05/2017
 * Time: 17:06
 */

namespace Beyerz\GoogleApiBundle\Service;


use Beyerz\GoogleApiBundle\Manager\CredentialsManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ServiceFactory
{
    use ContainerAwareTrait;

    public function generateService(CredentialsManager $credentialsManager, \Google_Service $service)
    {
        return new ServiceProvider($credentialsManager,$service);
    }
}