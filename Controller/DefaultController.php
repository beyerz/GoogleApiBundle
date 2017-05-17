<?php

namespace Beyerz\GoogleApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BeyerzGoogleApiBundle:Default:index.html.twig');
    }
}
