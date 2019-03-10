<?php

namespace Viloveul\Transport;

use Exception;
use Interop\Amqp;
use Viloveul\Transport\Contracts\Bus as IBus;
use Viloveul\Transport\Contracts\Passenger as IPassenger;

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
     * @var array
     */
    private $arguments = [];

    /**
     * @var array
     */
    private $attributes = [];

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

    /**
     * @return mixed
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @param $name
     * @param $default
     */
    public function getAttribute($name, $default = null)
    {
        return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    abstract public function handle(): void;

    abstract public function point(): string;

    public function process(): void
    {
        try {
            $this->initialize();
            $this->handle();
            $attributes = array_merge($this->attributes, [
                'id' => uniqid(),
                'task' => $this->task(),
                'args' => $this->getArguments(),
            ]);
            $message = $this->context->createMessage(
                json_encode($attributes)
            );
            $message->setContentType('application/json');

            if (is_null($this->queue)) {
                $this->queue = $this->context->createQueue($this->point());
                $this->queue->addFlag(Amqp\AmqpQueue::FLAG_DURABLE);
            }
            $this->context->declareQueue($this->queue);
            $this->producer->send($this->queue, $message);
        } catch (Exception $e) {
            $this->bus->addException($e);
        }
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments): void
    {
        $this->arguments = array_values($arguments);
    }

    /**
     * @param $name
     * @param $value
     */
    public function setAttribute($name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    abstract public function task(): string;

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
    }
}
