<?php
/**
 * Created by PhpStorm.
 * User: bailz777
 * Date: 18/05/2017
 * Time: 12:10
 */

namespace Beyerz\GoogleApiBundle\DependencyInjection\Compiler;


use Beyerz\GoogleApiBundle\Service\ServiceProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

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
        //generate the service definitions for all services registered in config
        if ($container->hasParameter('beyerz_google_api.services')) {
            foreach ($container->getParameter('beyerz_google_api.services') as $service) {
                $serviceClass = $this->getServiceClass($service);
                if (!$serviceClass) {
                    $suggestions = [];
                    //get potential options
                    foreach ($this->serviceClassMap() as $serviceName => $class) {
                        $lev = levenshtein($serviceName, $service);
                        if ($lev <= strlen($service) / 3) {
                            $suggestions[] = $serviceName;
                        }
                    }
                    throw new \Exception("Invalid service definition '" . $service . "', did you maybe mean: " . implode(", ", $suggestions));
                }

                $client = $container->getDefinition('beyerz_google_api.google_client');
                $client = clone $client;
//                $client->addMethodCall('setScopes', [$container->getParameter('beyerz_google_api.services.'.$service.'.scopes')]);
                $client->addMethodCall('setAccessType', [$container->getParameter('beyerz_google_api.services.'.$service.'.access_type')]);

                $serviceDefinition = new Definition($serviceClass, [$client]);
                $serviceDefinition->setPublic(false);

                $definition = new Definition(ServiceProvider::class, [new Reference('beyerz_google_api.credentials_manager'), $serviceDefinition]);

                $container->setDefinition('beyerz_google_api.service.' . $service, $definition);
            }
        }
    }

    private function getServiceClass($service)
    {

        $services = $this->serviceClassMap();

        return isset($services[$service]) ? $services[$service] : false;
    }

    /**
     * @return array
     */
    private function serviceClassMap()
    {
        return [
            'acceleratedmobilepageurl'      => \Google_Service_Acceleratedmobilepageurl::class,
            'ad_exchange_buyer'             => \Google_Service_AdExchangeBuyer::class,
            'ad_exchange_buyer_i_i'         => \Google_Service_AdExchangeBuyerII::class,
            'ad_exchange_seller'            => \Google_Service_AdExchangeSeller::class,
            'ad_sense'                      => \Google_Service_AdSense::class,
            'ad_sense_host'                 => \Google_Service_AdSenseHost::class,
            'analytics'                     => \Google_Service_Analytics::class,
            'analytics_reporting'           => \Google_Service_AnalyticsReporting::class,
            'android_enterprise'            => \Google_Service_AndroidEnterprise::class,
            'android_publisher'             => \Google_Service_AndroidPublisher::class,
            'appengine'                     => \Google_Service_Appengine::class,
            'appsactivity'                  => \Google_Service_Appsactivity::class,
            'app_state'                     => \Google_Service_AppState::class,
            'autoscaler'                    => \Google_Service_Autoscaler::class,
            'bigquery'                      => \Google_Service_Bigquery::class,
            'blogger'                       => \Google_Service_Blogger::class,
            'books'                         => \Google_Service_Books::class,
            'calendar'                      => \Google_Service_Calendar::class,
            'civic_info'                    => \Google_Service_CivicInfo::class,
            'classroom'                     => \Google_Service_Classroom::class,
            'cloudbilling'                  => \Google_Service_Cloudbilling::class,
            'cloud_build'                   => \Google_Service_CloudBuild::class,
            'cloud_debugger'                => \Google_Service_CloudDebugger::class,
            'clouderrorreporting'           => \Google_Service_Clouderrorreporting::class,
            'cloud_functions'               => \Google_Service_CloudFunctions::class,
            'cloud_k_m_s'                   => \Google_Service_CloudKMS::class,
            'cloudlatencytest'              => \Google_Service_Cloudlatencytest::class,
            'cloud_machine_learning'        => \Google_Service_CloudMachineLearning::class,
            'cloud_machine_learning_engine' => \Google_Service_CloudMachineLearningEngine::class,
            'cloud_monitoring'              => \Google_Service_CloudMonitoring::class,
            'cloud_natural_language'        => \Google_Service_CloudNaturalLanguage::class,
            'cloud_natural_language_a_p_i'  => \Google_Service_CloudNaturalLanguageAPI::class,
            'cloud_resource_manager'        => \Google_Service_CloudResourceManager::class,
            'cloud_runtime_config'          => \Google_Service_CloudRuntimeConfig::class,
            'cloud_source_repositories'     => \Google_Service_CloudSourceRepositories::class,
            'cloud_speech_a_p_i'            => \Google_Service_CloudSpeechAPI::class,
            'cloud_trace'                   => \Google_Service_CloudTrace::class,
            'cloud_user_accounts'           => \Google_Service_CloudUserAccounts::class,
            'compute'                       => \Google_Service_Compute::class,
            'consumer_surveys'              => \Google_Service_ConsumerSurveys::class,
            'container'                     => \Google_Service_Container::class,
            'coordinate'                    => \Google_Service_Coordinate::class,
            'customsearch'                  => \Google_Service_Customsearch::class,
            'dataflow'                      => \Google_Service_Dataflow::class,
            'dataproc'                      => \Google_Service_Dataproc::class,
            'datastore'                     => \Google_Service_Datastore::class,
            'data_transfer'                 => \Google_Service_DataTransfer::class,
            'deployment_manager'            => \Google_Service_DeploymentManager::class,
            'dfareporting'                  => \Google_Service_Dfareporting::class,
            'directory'                     => \Google_Service_Directory::class,
            'dns'                           => \Google_Service_Dns::class,
            'double_click_bid_manager'      => \Google_Service_DoubleClickBidManager::class,
            'doubleclicksearch'             => \Google_Service_Doubleclicksearch::class,
            'drive'                         => \Google_Service_Drive::class,
            'firebase_dynamic_links'        => \Google_Service_FirebaseDynamicLinks::class,
            'firebase_dynamic_links_a_p_i'  => \Google_Service_FirebaseDynamicLinksAPI::class,
            'firebase_rules_a_p_i'          => \Google_Service_FirebaseRulesAPI::class,
            'fitness'                       => \Google_Service_Fitness::class,
            'freebase'                      => \Google_Service_Freebase::class,
            'fusiontables'                  => \Google_Service_Fusiontables::class,
            'games'                         => \Google_Service_Games::class,
            'games_configuration'           => \Google_Service_GamesConfiguration::class,
            'games_management'              => \Google_Service_GamesManagement::class,
            'genomics'                      => \Google_Service_Genomics::class,
            'gmail'                         => \Google_Service_Gmail::class,
            'groups_migration'              => \Google_Service_GroupsMigration::class,
            'groupssettings'                => \Google_Service_Groupssettings::class,
            'iam'                           => \Google_Service_Iam::class,
            'identity_toolkit'              => \Google_Service_IdentityToolkit::class,
            'kgsearch'                      => \Google_Service_Kgsearch::class,
            'licensing'                     => \Google_Service_Licensing::class,
            'logging'                       => \Google_Service_Logging::class,
            'manager'                       => \Google_Service_Manager::class,
            'manufacturer_center'           => \Google_Service_ManufacturerCenter::class,
            'mirror'                        => \Google_Service_Mirror::class,
            'monitoring'                    => \Google_Service_Monitoring::class,
            'oauth2'                        => \Google_Service_Oauth2::class,
            'pagespeedonline'               => \Google_Service_Pagespeedonline::class,
            'partners'                      => \Google_Service_Partners::class,
            'people'                        => \Google_Service_People::class,
            'play_movies'                   => \Google_Service_PlayMovies::class,
            'playmoviespartner'             => \Google_Service_Playmoviespartner::class,
            'plus'                          => \Google_Service_Plus::class,
            'plus_domains'                  => \Google_Service_PlusDomains::class,
            'prediction'                    => \Google_Service_Prediction::class,
            'proximitybeacon'               => \Google_Service_Proximitybeacon::class,
            'pubsub'                        => \Google_Service_Pubsub::class,
            'q_p_x_express'                 => \Google_Service_QPXExpress::class,
            'replicapool'                   => \Google_Service_Replicapool::class,
            'replicapoolupdater'            => \Google_Service_Replicapoolupdater::class,
            'reports'                       => \Google_Service_Reports::class,
            'reseller'                      => \Google_Service_Reseller::class,
            'resourceviews'                 => \Google_Service_Resourceviews::class,
            'safebrowsing'                  => \Google_Service_Safebrowsing::class,
            'script'                        => \Google_Service_Script::class,
            'search_console'                => \Google_Service_SearchConsole::class,
            'service_control'               => \Google_Service_ServiceControl::class,
            'service_management'            => \Google_Service_ServiceManagement::class,
            'service_registry'              => \Google_Service_ServiceRegistry::class,
            'service_user'                  => \Google_Service_ServiceUser::class,
            'sheets'                        => \Google_Service_Sheets::class,
            'shopping_content'              => \Google_Service_ShoppingContent::class,
            'site_verification'             => \Google_Service_SiteVerification::class,
            'slides'                        => \Google_Service_Slides::class,
            'spanner'                       => \Google_Service_Spanner::class,
            'spectrum'                      => \Google_Service_Spectrum::class,
            'speech'                        => \Google_Service_Speech::class,
            's_q_l_admin'                   => \Google_Service_SQLAdmin::class,
            'storage'                       => \Google_Service_Storage::class,
            'storagetransfer'               => \Google_Service_Storagetransfer::class,
            'surveys'                       => \Google_Service_Surveys::class,
            'tag_manager'                   => \Google_Service_TagManager::class,
            'taskqueue'                     => \Google_Service_Taskqueue::class,
            'tasks'                         => \Google_Service_Tasks::class,
            'tool_results'                  => \Google_Service_ToolResults::class,
            'tracing'                       => \Google_Service_Tracing::class,
            'translate'                     => \Google_Service_Translate::class,
            'urlshortener'                  => \Google_Service_Urlshortener::class,
            'vision'                        => \Google_Service_Vision::class,
            'webfonts'                      => \Google_Service_Webfonts::class,
            'webmasters'                    => \Google_Service_Webmasters::class,
            'you_tube'                      => \Google_Service_YouTube::class,
            'you_tube_analytics'            => \Google_Service_YouTubeAnalytics::class,
            'you_tube_reporting'            => \Google_Service_YouTubeReporting::class,
        ];
    }

    /**
     * Used to create mapping array from files
     *
     * @return array
     */
    private function classToServiceMapper(ContainerBuilder $container)
    {
        $finder = new Finder();

        $finder->depth("== 0")->files()->in($container->getParameter('kernel.root_dir') . "/../vendor/google/apiclient-services/src/Google/Service");
        $services = [];
        foreach ($finder as $file) {
            if ($file->isFile()) {
                $fn = $file->getFilename();
                //convert to service name
                $nameConverter = new CamelCaseToSnakeCaseNameConverter();
                $service = str_replace(".php", "", $nameConverter->normalize($fn));
                $class = "Google_Service_" . str_replace(".php", "", $fn);
                $services[$service] = $class;
            }
        }

        return $services;
    }
}