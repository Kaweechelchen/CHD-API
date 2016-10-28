<?php

namespace CHD\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use CHD\Helpers\webRequest;
use CHD\Helpers\CHDScrapePath;
use CHD\Helpers\CHDPetitionPages;
use CHD\Helpers\CHDPetitionsFromPage;
use CHD\Helpers\CHDPetition;

class scrapeControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $ctr = $app['controllers_factory'];
        $ctr->get('/', function (Application $app) {
            $app['log']->debug('scrape started');
            $app['webRequest'] = new webRequest($app);
            $app['CHDFirstPage'] = $app['webRequest']->get($app['CHD']['list']['url']);
            $app['CHDScrapePath'] = new CHDScrapePath($app);
            $app['CHDPetitionPages'] = new CHDPetitionPages($app);
            $app['CHDPetitionsFromPage'] = new CHDPetitionsFromPage($app);
            $app['CHDPetition'] = new CHDPetition($app);

            $petitions = [];
            foreach ($app['CHDPetitionPages']->get() as $key => $CHDPetitionPage) {
                if ($app['debug'] && $key > 0) {
                    continue;
                }
                $petitions = array_merge(
                    $petitions,
                    $app['CHDPetitionsFromPage']->get($CHDPetitionPage)
                );
            }

            foreach ($petitions as $key => $petition) {
                if ($app['debug'] && $key > 0) {
                    continue;
                }
            }

            print_r($petitions);

            die();

            return $app['petitionList']->getIDs($app);

            //$CHDPetition     = new Helpers\CHDPetition();
            //$webRequest      = new Helpers\webRequest();

            return $webRequest->get('https://mona.lu');

            /*$lineIssues = Issues::getLineIssues($app);
            file_put_contents(
                'current.json',
                json_encode($lineIssues)
            );
            $count = 0;
            foreach ($lineIssues as $line => $issues) {
                foreach ($issues as $issue) {
                    $tweets[] = Storage::saveIssue($app, $issue, $line);
                    ++$count;
                }
            }
            if ($app[ 'debug' ]) {
                return $app->json($tweets);
            } else {
                return $count.' issued saved';
            }*/

            return 'hello';
        });

        return $ctr;
    }
}
