## SETUP

initilize bus connection for data transport

```php
use Viloveul\Transport\Bus;

$bus = new Bus();
$bus->addConnection('amqp://localhost:5672//');
```

declaring class passenger (for whatever your data)

```php
use Viloveul\Transport\Passenger;

class TaskPassenger extends Passenger
{
	public function point(): string
	{
		return 'queue name';
	}

	public function task(): string
	{
		return 'task';
	}

	public function handle(): void
	{
		$this->setAttribute('data', [
			'foo' => 'bar'
		]);
	}
}
```

## BROKER MESSAGE (RabbitMQ)
run rabbit under docker
```bash
docker run --rm -p 5672:5672 rabbitmq:3
# rabbitmq listen for port 5672
```

processing task

```php
$bus->process(new TaskPassenger);
```


## BACKEND (using Celery)

```shell
cd sample/celery
celery -A tasks worker -Q system_notification --loglevel=info
```

## BACKEND (websocket)

```shell
cd sample/socket.io
npm install
npm start
```