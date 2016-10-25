<?php

namespace CHD;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use CHD\Helpers\webRequest;
use CHD\Helpers\CHDPetitionList;

class scrapeControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $ctr = $app['controllers_factory'];
        $ctr->get('/', function (Application $app) {

            $app['log']->debug('scrape started');
            $app['webRequest'] = new webRequest();

            $CHDPetitionList = new CHDPetitionList();

            return $CHDPetitionList->getIDs($app);

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
