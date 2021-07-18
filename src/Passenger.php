<?php

namespace Viloveul\Transport;

use Interop\Amqp;
use Interop\Queue\Context;
use Interop\Amqp\AmqpTopic;
use Interop\Amqp\Impl\AmqpTopic as AmqpTopicImpl;
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
    protected $topic = null;

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

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param $name
     * @param $default
     */
    public function getAttribute($name, $default = null)
    {
        return array_key_exists($name, $this->attributes) ? $this->attributes[$name] : $default;
    }

    public function initialize(): void
    {
        $this->producer = $this->context->createProducer();
        $this->topic = new AmqpTopicImpl($this->point());
        $this->topic->setType(AmqpTopic::TYPE_TOPIC);
        $this->context->declareTopic($this->topic);
    }

    public function run(): void
    {
        $this->handle();
        $message = $this->context->createMessage($this->data());
        $message->setContentType('application/json');
        $message->setRoutingKey($this->route());
        $this->producer->send($this->topic, $message);
    }

    /**
     * @param $name
     * @param $value
     */
    public function setAttribute($name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function with(Context $context): void
    {
        $this->context = $context;
    }
}
