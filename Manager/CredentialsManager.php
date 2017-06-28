<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 17/05/2017
 * Time: 17:21
 */

namespace Beyerz\GoogleApiBundle\Manager;


class CredentialsManager
{
    /**
     * @var string
     */
    private $credentialsPath = '~/.credentials/gmail-php-quickstart.json';

    /**
     * credentialsManager constructor.
     * @param string $credentialsPath
     */
    public function __construct($credentialsPath = null)
    {
        if (!is_null($credentialsPath)) {
            $this->credentialsPath = $credentialsPath;
        }
        $this->credentialsPath = $this->expandHomeDirectory($this->credentialsPath);
    }

    /**
     * @return bool
     */
    public function hasCredentials($user)
    {
        if (file_exists($this->credentialsPath)) {
            $db = $this->getDB();
            return array_key_exists($user,$db);
        }

        return false;
    }

    /**
     * @return array
     */
    public function getCredentials($user)
    {
        $db = $this->getDB();

        return $db[$user];
    }

    public function getDB(){
        if (!file_exists($this->credentialsPath)) {
            return [];
        }

        return json_decode(file_get_contents($this->credentialsPath), true);
    }

    /**
     * @param array $credentials
     * @return bool|int
     */
    public function createCredentials($user, array $credentials)
    {
        if (!file_exists(dirname($this->credentialsPath))) {
            mkdir(dirname($this->credentialsPath), 0700, true);
        }
        $db = $this->getDB();
        $db = array_merge($db, [$user => $credentials]);

        return file_put_contents($this->credentialsPath, json_encode($db));
    }

    /**
     * @return string
     */
    public function getCredentialsPath()
    {
        return $this->credentialsPath;
    }

    /**
     * @param $path
     * @return mixed
     */
    private function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
        }

        return str_replace('~', realpath($homeDirectory), $path);
    }

}