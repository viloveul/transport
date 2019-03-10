<?php

namespace Viloveul\Transport\Contracts;

use Viloveul\Transport\Contracts\Bus;

interface Passenger
{
    /**
     * @return mixed
     */
    public function getArguments(): array;

    /**
     * @param $name
     * @param $default
     */
    public function getAttribute($name, $default = null);

    public function process(): void;

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments): void;

    /**
     * @param $name
     * @param $value
     */
    public function setAttribute($name, $value): void;

    /**
     * @param Bus $bus
     */
    public function with(Bus $bus): void;
}
