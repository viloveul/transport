<?php

namespace Viloveul\Transport;

use Enqueue\AmqpLib\AmqpConnectionFactory;
use Exception;
use Interop\Queue\ConnectionFactory;
use Interop\Queue\Context;
use Viloveul\Transport\Contracts\Bus as IBus;

class Bus implements IBus
{
    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @var array
     */
    protected $exceptions = [];

    /**
     * @param Exception $e
     */
    public function addException(Exception $e)
    {
        $this->exceptions[] = $e;
    }

    /**
     * @param  string  $name
     * @return mixed
     */
    public function build(string $name = 'default'): Context
    {
        return $this->getConnection($name)->createContext();
    }

    /**
     * @param  string  $name
     * @return mixed
     */
    public function getConnection(string $name = 'default'): ConnectionFactory
    {
        return $this->connections[$name];
    }

    /**
     * @return mixed
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * @param string $name
     */
    public function hasConnection(string $name = 'default'): bool
    {
        return array_key_exists($name, $this->connections);
    }

    /**
     * @param $dsn
     * @param string $name
     */
    public function setConnection($dsn, string $name = 'default'): void
    {
        try {
            $this->connections[$name] = new AmqpConnectionFactory($dsn);
        } catch (Exception $e) {
            $this->addException($e);
        }
    }
}
