<?php

require_once __DIR__ . '/vendor/autoload.php';

$bus = new Viloveul\Transport\Bus();
$bus->setConnection('amqp://localhost:5672//');
$bus->process(new ViloveulTransportSample\TaskPassenger);