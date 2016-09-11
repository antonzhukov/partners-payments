<?php

// Define paths and autoload
require_once __DIR__ . '/vendor/autoload.php';
$config = require_once __DIR__ . '/config.php';
define('DATA_DIR', __DIR__ . '/data/');

// Configure dependencies
$partnerManager = new \PartnersPayments\Model\PartnerManager(
    new \PartnersPayments\Model\Events\Csv($config['events']),
    new PartnersPayments\Model\PaymentsProcessor\Csv($config['payments']),
    new \PartnersPayments\Model\Currency(),
    new \PartnersPayments\Model\Partners()
);

// Begin application
echo 'Begin parsing events...' . PHP_EOL . PHP_EOL;
$paymentsProcessed = $partnerManager->processPartnersNewEvents();
echo $paymentsProcessed . ' payments generated' . PHP_EOL;
