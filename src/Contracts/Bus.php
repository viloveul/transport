<?php

namespace Viloveul\Transport\Contracts;

use Closure;
use Interop\Queue\Context;
use Interop\Queue\ConnectionFactory;
use Viloveul\Transport\Contracts\Passenger;
use Viloveul\Transport\Contracts\ErrorCollection;

interface Bus
{
    /**
     * @param $dsn
     * @param string $name
     */
    public function addConnection($dsn, string $name = 'default');

    /**
     * @param string $name
     */
    public function build(string $name = 'default'): Context;

    /**
     * @param Closure $callback
     */
    public function error(Closure $callback): ErrorCollection;

    /**
     * @param string $name
     */
    public function getConnection(string $name = 'default'): ConnectionFactory;

    /**
     * @param string $name
     */
    public function hasConnection(string $name = 'default'): bool;

    public function initialize(): void;

    /**
     * @param Passenger $passenger
     */
    public function process(Passenger $passenger): void;
}
