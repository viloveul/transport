<?php

namespace Viloveul\Transport;

use Interop\Amqp;
use Interop\Queue\Context;
use Viloveul\Transport\Contracts\Passenger as IPassenger;

abstract class Passenger implements IPassenger
{
    /**
     * @var mixed
     */
    protected $context = null;

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

    public function __invoke()
    {
        $this->run();
    }

    public function connection(): string
    {
        return 'default';
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

    public function initialize(): void
    {
        $this->producer = $this->context->createProducer();
    }

    abstract public function point(): string;

    public function run(): void
    {
        $this->handle();
        $attributes = array_merge($this->attributes, [
            'id' => 'viloveul_' . uniqid(),
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

    public function with(Context $context): void
    {
        $this->context = $context;
    }
}
