<?php

namespace ViloveulTransportSample;

use Viloveul\Transport\Passenger;

class TaskPassenger extends Passenger
{
    public function handle(): void
    {
    	$this->setAttribute('user_id', 1);
        $this->setAttribute('data', [
            'total' => 50,
        ]);
        $this->setArguments(['your@mail.com', 'hello']);
    }

    public function point(): string
    {
        return 'system_notification';
    }

    public function task(): string
    {
        return 'my.task';
    }
}
