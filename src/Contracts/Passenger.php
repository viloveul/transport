<?php

namespace Viloveul\Transport\Contracts;

use Viloveul\Transport\Contracts\Bus;

interface Passenger
{
    public function process(): void;

    /**
     * @param Bus $bus
     */
    public function with(Bus $bus): void;
}
