<?php

namespace PartnersPayments\Item;

/**
 * Class Partner
 *
 * @package PartnersPayments\Item
 * @property-read int    $id
 * @property-read array  $events
 * @property-read int    $eventsNumber
 * @property-read string $period
 */
class Partner extends AbstractItem
{
    /**
     * @param Event $event
     *
     * @return void
     */
    public function addEvent(Event $event)
    {
        $this->data['events'][] = $event;
        $this->data['eventsNumber'] ++;
    }

    /**
     * @param string $period
     *
     * @return void
     */
    public function setPeriod($period)
    {
        $this->data['period'] = $period;
    }
}
