<?php

namespace Beyerz\GoogleApiBundle;

use Beyerz\GoogleApiBundle\DependencyInjection\Compiler\GoogleServicePass;
use Beyerz\GoogleApiBundle\DependencyInjection\Compiler\GoogleClientPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BeyerzGoogleApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GoogleClientPass());
        $container->addCompilerPass(new GoogleServicePass());
    }
}
