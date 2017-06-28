<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 28/06/2017
 * Time: 18:31
 */

namespace Beyerz\GoogleApiBundle\Manager;


interface UserProviderInterface
{
    /**
     * @return boolean
     */
    public function hasCredentials();

    /**
     * @return mixed
     */
    public function getCredentials();

    /**
     * @return mixed
     */
    public function createCredentials();
}