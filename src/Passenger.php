<?php

namespace Viloveul\Transport;

use Exception;
use Interop\Amqp;
use Viloveul\Transport\Contracts\Bus as IBus;
use Viloveul\Transport\Contracts\Passenger as IPassenger;
use JsonSerializable;

abstract class Passenger implements IPassenger
{
    /**
     * @var mixed
     */
    protected $bus;

    /**
     * @var mixed
     */
    protected $context = null;

    /**
     * @var string
     */
    protected $driver = 'default';

    /**
     * @var mixed
     */
    protected $producer = null;

    /**
     * @var mixed
     */
    protected $queue = null;

    /**
     * @var mixed
     */
    protected $topic = null;

    /**
     * @param IBus $bus
     */
    public function __construct(IBus $bus)
    {
        $this->with($bus);
    }

    public function __invoke()
    {
        $this->process();
    }

    abstract public function handle(): JsonSerializable;

    abstract public function point(): string;

    public function process(): void
    {
        try {
            $this->initialize();
            $message = $this->context->createMessage(
                json_encode($this->handle())
            );
            $message->setContentType('application/json');
            $this->context->declareQueue($this->queue);
            $this->producer->send($this->queue, $message);
        } catch (Exception $e) {
            $this->bus->addException($e);
        }
    }

    /**
     * @param IBus $bus
     */
    public function with(IBus $bus): void
    {
        $this->bus = $bus;
    }

    protected function initialize()
    {
        $this->context = $this->bus->build($this->driver);
        $this->producer = $this->context->createProducer();
        $this->queue = $this->context->createQueue($this->point());
    }
}
