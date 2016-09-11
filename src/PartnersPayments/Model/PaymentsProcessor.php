<?php

namespace PartnersPayments\Model;

use PartnersPayments\Item\PaymentCollection;

/**
 * Class Events
 *
 * @package PartnersPayments\Model
 */
interface PaymentsProcessor
{
    /**
     * @param PaymentCollection $payments
     *
     * @return bool
     */
    public function save(PaymentCollection $payments);
}
