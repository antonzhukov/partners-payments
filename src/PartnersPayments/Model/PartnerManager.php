<?php

namespace PartnersPayments\Model;

use PartnersPayments\Item\Event;
use PartnersPayments\Item\EventCollection;
use PartnersPayments\Item\Partner;
use PartnersPayments\Item\PartnerCollection;
use PartnersPayments\Item\Payment;
use PartnersPayments\Item\PaymentCollection;

/**
 * Class PartnerManager
 *
 * @package PartnersPayments\Model
 */
class PartnerManager
{
    const PERIOD_WEEKLY = 'weekly';
    const PERIOD_BIWEEKLY = 'bi-weekly';
    const PERIOD_MONTHLY = 'monthly';

    /**
     * @var Events
     */
    protected $events;

    /**
     * @var PaymentsProcessor
     */
    protected $paymentsProcessor;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * @var Partners
     */
    protected $partners;
    
    /**
     * PartnerManager constructor.
     * @param Events            $events
     * @param PaymentsProcessor $paymentsProcessor
     * @param Currency          $currency
     * @param Partners          $partners
     */
    public function __construct(
        Events $events,
        PaymentsProcessor $paymentsProcessor,
        Currency $currency,
        Partners $partners
    )
    {
        $this->events = $events;
        $this->paymentsProcessor = $paymentsProcessor;
        $this->currency = $currency;
        $this->partners = $partners;
    }

    /**
     * @return int
     */
    public function processPartnersNewEvents()
    {
        // Get new events
        $newEvents = $this->events->getNewEvents();

        // Prepare partners payments
        $partners = $this->fetchEventsForPartners($newEvents);
        $this->calculatePaymentPeriodForPartners($partners);
        $payments = $this->calculatePayments($partners);

        // Send payments
        $this->paymentsProcessor->save($payments);
        return $payments->count();
    }

    /**
     * @param \PartnersPayments\Item\EventCollection $newEvents
     *
     * @return \PartnersPayments\Item\PartnerCollection
     */
    protected function fetchEventsForPartners(EventCollection $newEvents)
    {
        $partners = [];

        foreach ($newEvents as $newEvent) {
            /** @var Event $newEvent */
            if (!isset($partners[$newEvent->partner])) {
                $partners[$newEvent->partner] = $this->partners->getPartner($newEvent->partner);
            }
            /** @var Partner $partner */
            $partner = $partners[$newEvent->partner];
            $partner->addEvent($newEvent);
        }
        
        return new PartnerCollection($partners);
    }

    /**
     * @param \PartnersPayments\Item\PartnerCollection $partnerCollection
     *
     * @return void
     */
    protected function calculatePaymentPeriodForPartners(PartnerCollection $partnerCollection)
    {
        foreach ($partnerCollection as $partner) {
            $paymentPeriod = null;
            /** @var Partner $partner */
            if ($partner->eventsNumber > 5) {
                $paymentPeriod = self::PERIOD_WEEKLY;
            } elseif ($partner->eventsNumber <= 5 && $partner->eventsNumber >= 3) {
                $paymentPeriod = self::PERIOD_BIWEEKLY;
            } else {
                $paymentPeriod = self::PERIOD_MONTHLY;
            }
            $partner->setPeriod($paymentPeriod);
        }
    }

    /**
     * @param \PartnersPayments\Item\PartnerCollection $partnerCollection
     *
     * @return \PartnersPayments\Item\PaymentCollection
     */
    protected function calculatePayments(PartnerCollection $partnerCollection)
    {
        $payments = [];
        foreach ($partnerCollection as $partner) {
            /** @var Partner $partner */
            foreach ($partner->events as $event) {
                /** @var Event $event */
                $eventDate = $event->timestamp;
                $paymentDate = strtotime('+4 days', $eventDate);
                // Check if payment date is weekend
                if (($weekday = date('N', $paymentDate)) >= 6) {
                    $paymentDate = strtotime('next Wednesday', $paymentDate);
                }

                $payments[] = new Payment([
                    'partner_id' => $partner->id,
                    'amount' => $this->currency->convertToDefault($event->amount, $event->currency),
                    'date' => $paymentDate,
                    'period' => $partner->period,
                ]);
                
            }

        }

        return new PaymentCollection($payments);
    }
}
