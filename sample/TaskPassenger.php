<?php

namespace ViloveulTransportSample;

use Viloveul\Transport\Passenger;

class TaskPassenger extends Passenger
{
    public function handle(): void
    {
        $this->setAttribute('user_id', 1);
        $this->setAttribute('body', [
            'total' => 50,
        ]);
    }

    public function data(): string
    {
        return json_encode($this->getAttributes());
    }

    public function point(): string
    {
        return 'system_notification';
    }

    public function route(): string
    {
        return 'my.task';
    }
}
