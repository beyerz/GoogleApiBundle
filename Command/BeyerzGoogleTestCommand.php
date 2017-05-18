<?php

namespace Beyerz\GoogleApiBundle\Command;

use Beyerz\GoogleApiBundle\Manager\CredentialsManager;
use Beyerz\GoogleApiBundle\Service\ServiceProvider;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

define('APPLICATION_NAME', 'Gmail API PHP Quickstart');
define('CREDENTIALS_PATH', '~/.credentials/gmail-php-quickstart.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/gmail-php-quickstart.json
define('SCOPES', implode(' ', [
        \Google_Service_Gmail::GMAIL_READONLY,
    ]
));

class BeyerzGoogleTestCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('beyerz:google-test')
            ->setDescription('...');
//            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $service = $this->getContainer()->get('beyerz_google_api.service.gmail');
        $service = $this->getContainer()->get('beyerz_google_api.proxy_service.gmail');
//        die;

//        $user = '108384709513204140011'; //lance
//        $user = '104937493445230534804'; //peleg
        $user = '116734602578676449600'; //eli

        $results = $service->users_labels($user)->listUsersLabels($user);

        $base64url_decode = function ($data){
            return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
        };

//        dump($service->getResources());die;
        $emailsList = $service->users_messages($user)->listUsersMessages($user);
        if (count($emailsList->getMessages()) == 0) {
            print "No messages found.\n";
        } else {
            print "Messages:\n";
            /** @var \Google_Service_Gmail_Message $message */
            foreach ($emailsList->getMessages() as $message) {
                /** @var \Google_Service_Gmail_Message $email */
                $email = $service->users_messages($user)->get($user,$message->getId()/*,['format'=>'raw']*/);

//                var_dump($base64url_decode($email->getRaw()));
//                dump(get_class($email));
//                die;


                /** @var \Google_Service_Gmail_MessagePart $payload */
                $payload = $email->getPayload();
                /** @var \Google_Service_Gmail_MessagePart[] $parts */
                $parts = $payload->getParts();
var_dump($parts);die;
                foreach ($parts as $part){
                    /** @var \Google_Service_Gmail_MessagePartBody $body */
                    $body = $part->getBody();
                    dump($base64url_decode($body->getData()));
                    die;
                }
//                dump($message);
//                printf("- %s\n", $message);
            }
        }


        if (count($results->getLabels()) == 0) {
            print "No labels found.\n";
        } else {
            print "Labels:\n";
            foreach ($results->getLabels() as $label) {
                printf("- %s\n", $label->getName());
            }
        }

    }

    private function getClient()
    {
        $client = $this->getContainer()->get('beyerz_google_api.google_client');
//        $client = new \Google_Client();
//        $client->setApplicationName($this->getContainer()->getParameter('beyerz_google_api.application_name'));
//        $client->setScopes($this->getContainer()->getParameter('beyerz_google_api.service.gmail.scopes'));
//        $client->setAuthConfig(sprintf('%s/%s',$this->getContainer()->get('kernel')->getRootDir(),$this->getContainer()->getParameter('beyerz_google_api.client_secret_path')));
//        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        return $client;
    }

}
