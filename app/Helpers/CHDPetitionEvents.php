<?php

namespace CHD\Helpers;

class CHDPetitionEvents
{
    protected $app;
    public function __construct($app)
    {
        $this->app = $app;
    }
    public function get($eventsTable)
    {
        $newEvents    = [];
        $eventPattern = '/<td[^>]*>(?P<date>.*?)<\/td> <td[^>]*>(?P<event>.*?)<\/td> <td[^>]*>(?P<link>.*?)<\/td>/';

        $events = explode('</tr> <tr', $eventsTable);

        foreach ($events as $key => $event) {
            if ($key == 0) {
                continue;
            }

            if (!preg_match($eventPattern, $event, $eventMatches)) {
                throw new Exception('eventdata not matching');
            }

            foreach ($eventMatches as $key => $eventMatch) {
                if ($key == 0) {
                    continue;
                }

                $eventMatches[$key] = $this->br2nl($eventMatch);
            }

            $linkPattern = '/openWindow\(\'(?P<link>[^\']*)\'.*">(?P<name>[^<]*)/';

            if (preg_match($linkPattern, $eventMatches['link'], $linkMatches)) {
                $link = [
                    'name' => $linkMatches['name'],
                    'link' => $this->app['CHD']['host'].$linkMatches['link'],
                ];
            } else {
                $link = null;
            }

            $event = [
                'date'  => strtotime($eventMatches['date']),
                'event' => $this->br2nl($eventMatches['event']),
                'links' => $link,
            ];

            $newEvents[] = $event;
        }

        return $newEvents;
    }

    protected function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
}
