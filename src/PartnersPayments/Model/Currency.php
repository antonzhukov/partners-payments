<?php

namespace PartnersPayments\Model;

/**
 * Class Events
 *
 * @package PartnersPayments\Model
 */
class Currency
{
    const EUR = 'EUR';
    const GBP = 'GBP';
    const USD = 'USD';

    protected $defaultCurrency = self::EUR;

    protected $exchangeRates = [
        self::GBP => 1.4,
        self::USD => 0.9,
    ];

    /**
     * @param float  $amount
     * @param string $sourceCurrency
     *
     * @return float|null
     */
    public function convertToDefault($amount, $sourceCurrency)
    {
        if (isset($this->exchangeRates[$sourceCurrency])) {
            $rate = $this->exchangeRates[$sourceCurrency];
            return round($amount * $rate, 2);
        } elseif ($sourceCurrency === self::EUR) {
            return $amount;
        }

        return null;
    }
}
