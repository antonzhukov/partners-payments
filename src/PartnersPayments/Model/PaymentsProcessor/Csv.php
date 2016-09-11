<?php

namespace PartnersPayments\Model\PaymentsProcessor;

use PartnersPayments\Item\Payment;
use PartnersPayments\Item\PaymentCollection;
use PartnersPayments\Model\PaymentsProcessor;

/**
 * Class Csv
 *
 * @package PartnersPayments\Model\PaymentsProcessor
 */
class Csv implements PaymentsProcessor
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
     * @param PaymentCollection $payments
     *
     * @return bool
     */
    public function save(PaymentCollection $payments)
    {
        if (($handle = fopen(DATA_DIR . $this->config['filename'], 'w')) !== false) {
            // File header
            fputcsv($handle, [
                'partner_id',
                'amount',
                'date',
                'period'
            ]);

            foreach ($payments as $payment) {
                /** @var Payment $payment */
                $data = $payment->toArray();
                $data['date'] = date('Y-m-d H:i:s', $data['date']);
                fputcsv($handle, $data);
            }
            fclose($handle);
        }

        return true;
    }
}
