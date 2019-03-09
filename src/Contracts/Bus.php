<?php

namespace Viloveul\Transport\Contracts;

use Exception;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Context;

interface Bus
{
    /**
     * @param Exception $e
     */
    public function addException(Exception $e);

    /**
     * @param string $name
     */
    public function build(string $name = 'default'): Context;

    /**
     * @param string $name
     */
    public function getConnection(string $name = 'default'): ConnectionFactory;

    public function getExceptions();

    /**
     * @param string $name
     */
    public function hasConnection(string $name = 'default'): bool;

    /**
     * @param $dsn
     * @param string $name
     */
    public function setConnection($dsn, string $name = 'default');
}
