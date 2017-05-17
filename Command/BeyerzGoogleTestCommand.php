<?php

namespace Beyerz\GoogleApiBundle\Command;

use Beyerz\GoogleApiBundle\Manager\credentialsManager;
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
        $client = $this->getClient();
        $service = new \Google_Service_Gmail($client);

        // Print the labels in the user's account.
        $user = 'me';
        $results = $service->users_labels->listUsersLabels($user);
        $emailsList = $service->users_messages->listUsersMessages($user);
        if (count($emailsList->getMessages()) == 0) {
            print "No messages found.\n";
        } else {
            print "Messages:\n";
            /** @var \Google_Service_Gmail_Message $message */
            foreach ($emailsList->getMessages() as $message) {
                $email = $service->users_messages->get($user,$message->getId());
                /** @var \Google_Service_Gmail_MessagePart $payload */
                $payload = $email->getPayload();
                /** @var \Google_Service_Gmail_MessagePart[] $parts */
                $parts = $payload->getParts();

                foreach ($parts as $part){
                    /** @var \Google_Service_Gmail_MessagePartBody $body */
                    $body = $part->getBody();
                    dump(base64_decode($body->getData()));
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
        $client = new \Google_Client();
        $client->setApplicationName($this->getContainer()->getParameter('beyerz_google_api.application_name'));
        $client->setScopes($this->getContainer()->getParameter('beyerz_google_api.service.gmail.scopes'));
        $client->setAuthConfig(sprintf('%s/%s',$this->getContainer()->get('kernel')->getRootDir(),$this->getContainer()->getParameter('beyerz_google_api.client_secret_path')));
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsManager = new credentialsManager();

        if($credentialsManager->hasCredentials()){
            $credentials = $credentialsManager->getCredentials();
        }else{
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $credentials = $client->fetchAccessTokenWithAuthCode($authCode);

            if($credentialsManager->createCredentials($credentials)) {
                printf("Credentials saved to %s\n", $credentialsManager->getCredentialsPath());
            }
        }
        $client->setAccessToken($credentials);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $credentialsManager->createCredentials($client->getAccessToken());
        }
        return $client;
    }

}
