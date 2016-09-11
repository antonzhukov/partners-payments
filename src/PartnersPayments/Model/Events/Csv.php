<?php

namespace PartnersPayments\Model\Events;

use PartnersPayments\Item\Event;
use PartnersPayments\Item\EventCollection;
use PartnersPayments\Model\Events;

/**
 * Class Csv
 *
 * @package PartnersPayments\Model\Events
 */
class Csv implements Events
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Csv constructor.
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @return EventCollection
     */
    public function getNewEvents()
    {
        $events = [];
        $started = false;
        if (($handle = fopen(DATA_DIR . $this->config['filename'], 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                if (!$started) {
                    $started = true;
                    continue;
                }

                if (count($data) !== 5) {
                    continue;
                }

                $events[] = new Event([
                    'id' => $data[0],
                    'timestamp' => strtotime($data[1]),
                    'partner' => $data[2],
                    'amount' => $data[3],
                    'currency' => $data[4],
                ]);
            }
            fclose($handle);
        }

        return new EventCollection($events);
    }
}
