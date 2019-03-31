<?php

namespace Viloveul\Transport;

use Closure;
use Exception;
use Interop\Queue\Context;
use Interop\Queue\ConnectionFactory;
use Viloveul\Transport\ErrorCollection;
use Enqueue\AmqpLib\AmqpConnectionFactory;
use Viloveul\Transport\Contracts\Bus as IBus;
use Viloveul\Transport\Contracts\Passenger as IPassenger;
use Viloveul\Transport\Contracts\ErrorCollection as IErrorCollection;

class Bus implements IBus
{
    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @var array
     */
    protected $errorCollection;

    /**
     * @var mixed
     */
    protected $initialized = false;

    /**
     * @param $dsn
     * @param string $name
     */
    public function addConnection($dsn, string $name = 'default'): void
    {
        try {
            $this->connections[$name] = new AmqpConnectionFactory($dsn);
        } catch (Exception $e) {
            $this->errorCollection->add($e);
        }
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
     * @param  Closure $callback
     * @return mixed
     */
    public function error(Closure $callback = null): IErrorCollection
    {
        if ($callback === null) {
            return $this->errorCollection;
        }
        return $callback($this->errorCollection);
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
     * @param string $name
     */
    public function hasConnection(string $name = 'default'): bool
    {
        return array_key_exists($name, $this->connections);
    }

    public function initialize():void
    {
        $this->initialized = true;
        if (!($this->errorCollection instanceof IErrorCollection)) {
            $this->errorCollection = new ErrorCollection();
        }
    }

    /**
     * @param IPassenger $passenger
     */
    public function process(IPassenger $passenger): void
    {
        try {
            $context = $this->build($passenger->connection());
            $passenger->with($context);
            $passenger->initialize();
            $passenger->run();
        } catch (Exception $e) {
            $this->errorCollection->add($e);
        }
    }
}
