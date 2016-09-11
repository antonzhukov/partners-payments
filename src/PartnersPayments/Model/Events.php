<?php

namespace PartnersPayments\Model;

use PartnersPayments\Item\EventCollection;

/**
 * Class Events
 *
 * @package PartnersPayments\Model
 */
interface Events
{
    /**
     * @return EventCollection
     */
    public function getNewEvents();
}
