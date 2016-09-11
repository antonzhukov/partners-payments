<?php

namespace PartnersPayments\Model;

use PartnersPayments\Item\Partner;

/**
 * Class Partners
 *
 * @package PartnersPayments\Model
 */
class Partners
{
    /**
     * @param int $id
     *
     * @return Partner
     */
    public function getPartner($id)
    {
        $partnerData = [
            'id' => $id,
            'events' => [],
            'eventsNumber' => 0,
            'period' => '',
        ];
        return new Partner($partnerData);
    }
}
