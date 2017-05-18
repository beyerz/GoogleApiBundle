<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 18/05/2017
 * Time: 14:48
 */

namespace Beyerz\GoogleApiBundle\Service;


use Beyerz\GoogleApiBundle\Manager\CredentialsManager;

/**
 * Class ServiceProvider
 * @package Beyerz\GoogleApiBundle\Service
 *
 * @method users_labels(int $user)
 */
class ServiceProvider
{

    /**
     * @var CredentialsManager
     */
    private $credentialsManager;

    /**
     * @var \Google_Service
     */
    private $service;

    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * ServiceProvider constructor.
     * @param CredentialsManager $credentialsManager
     * @param \Google_Service $service
     */
    public function __construct(CredentialsManager $credentialsManager, \Google_Service $service)
    {
        $this->credentialsManager = $credentialsManager;
        $this->service = $service;
        $this->client = $service->getClient();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if(method_exists($this->service,$name)){
            return call_user_func_array([$this->service,$name],$arguments);
        }
        if(property_exists($this->service,$name) && $this->service->{$name} instanceof \Google_Service_Resource){
            //calling a property
            $user = $arguments[0];
            $this->credentials($user);
            return $this->service->{$name};
        }
    }

    /**
     * @return array
     */
    public function getResources(){
        $rf = new \ReflectionClass($this->service);
        $resources = [];
        foreach ($rf->getProperties() as $property){
            if($property->getValue($this->service) instanceof \Google_Service_Resource) {
                $resources[] = sprintf('%s(int $user)',$property->getName());
            }
        }
        return $resources;
    }

    /**
     * @param $user
     */
    private function credentials($user){
        if($this->credentialsManager->hasCredentials($user)){
            $credentials = $this->credentialsManager->getCredentials($user);
        }else{
            // Request authorization from the user.
            $authUrl = $this->client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $credentials = $this->client->fetchAccessTokenWithAuthCode($authCode);

            if($this->credentialsManager->createCredentials($user, $credentials)) {
                printf("Credentials saved to %s\n", $this->credentialsManager->getCredentialsPath());
            }
        }
        $this->client->setAccessToken($credentials);

        // Refresh the token if it's expired.
        if ($this->client->isAccessTokenExpired()) {
            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            $this->credentialsManager->createCredentials($user, $this->client->getAccessToken());
        }
    }

}