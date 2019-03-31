<?php

require_once __DIR__ . '/vendor/autoload.php';

$bus = new Viloveul\Transport\Bus();
$bus->addConnection('amqp://localhost:5672//');
$bus->process(new ViloveulTransportSample\TaskPassenger);
$error = $bus->error();

echo $error->count();
echo PHP_EOL;

print_r($error->top());
echo PHP_EOL;

print_r($error->all());
echo PHP_EOL;
