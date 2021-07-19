<?php

namespace ViloveulTransportSample;

use Viloveul\Transport\Passenger;

class TaskPassenger extends Passenger
{
    protected $type = 'direct';

    public function handle(): void
    {

    }

    public function data(): string
    {
        return json_encode([
            'id' => uniqid(),
            'task' => 'my.task',
            'kwargs' => [
                'foo' => 'Fajrul',
                'bar' => 'Viloveul'
            ]
        ]);
    }

    public function point(): string
    {
        return 'celery';
    }

    public function route(): string
    {
        return 'celery';
    }
}
