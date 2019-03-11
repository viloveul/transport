<?php

require_once __DIR__ . '/vendor/autoload.php';

$bus = new Viloveul\Transport\Bus();
$bus->setConnection('amqp://localhost:5672//');

// print_r($bus->getExceptions());exit;

$passenger = new ViloveulTransportSample\TaskPassenger($bus);
$passenger();