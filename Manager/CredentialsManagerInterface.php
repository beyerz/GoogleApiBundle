<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 28/06/2017
 * Time: 18:31
 */

namespace Beyerz\GoogleApiBundle\Manager;


interface CredentialsManagerInterface
{
    /**
     * @param $user
     * @return bool
     */
    public function hasCredentials($user);

    /**
     * @param $user
     * @return mixed
     */
    public function getCredentials($user);

    /**
     * @param $user
     * @param array $credentials
     * @return mixed
     */
    public function createCredentials($user, array $credentials);
}