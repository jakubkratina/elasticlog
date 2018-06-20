# Installation

Require package via composer
```
composer require jakubkratina/elasticlog
```

# Configuration
```
ELASTIC_ENABLED=true
ELASTIC_INDEX=index-name
ELASTIC_HOST_PORT=elasticsearch.example.com:9200
``` 

# Register logger 

### Laravel 

```php
$app->bind(
    \JK\Elasticlog\Contracts\Elasticsearch\Client::class,
    function () {
        if (env('ELASTIC_ENABLED', false) === true) {
            return new \JK\Elasticlog\Elasticsearch\Client(
                \Elasticsearch\ClientBuilder::create()->setHosts([env('ELASTIC_HOST_PORT')])->build(),
                env('ELASTIC_INDEX')
            );
        }

        return new \JK\Elasticlog\Elasticsearch\NullClient();
    }
);
```

### Symfony

You can register client via factory class: 
##### Register client into DI
```neon
    app.service.elasticlog:
        factory: ['AppBundle\Logger\ClientFactory', create]
```

##### Create factory class
```php
use JK\Elasticlog\Contracts\Elasticsearch\Client;
use JK\Elasticlog\Elasticsearch\Client as ElasticClient;
use JK\Elasticlog\Elasticsearch\NullClient;
use Elasticsearch\ClientBuilder;

final class ClientFactory
{
    /**
     * @return Client
     */
    public static function create(): Client
    {
        return self::isElasticsearchEnabled()
            ? self::createElasticClient()
            : self::createNullClient();
    }

    /**
     * @return Client
     */
    private static function createNullClient(): Client
    {
        return new NullClient();
    }

    /**
     * @return Client
     */
    private static function createElasticClient(): Client
    {
        return new ElasticClient(
            ClientBuilder::create()->setHosts([getenv('ELASTIC_HOST_PORT')])->build(), getenv('ELASTIC_INDEX')
        );
    }

    /**
     * @return bool
     */
    private static function isElasticsearchEnabled(): bool
    {
        return getenv('ELASTIC_ENABLED') === 'true';
    }
}
```

# Usage
## Create a message
Create a new class extending from `JK\Elasticlog\Log\Message` and implement the `toArray` method.

```php
class MyCustomMessage extends \JK\Elasticlog\Log\Message
{
    public function toArray(): array
    {
        return [
            'foo' => 'bar'
        ];
    }
}
```

You are free to pass parameters via constructor:

```php
class MyCustomMessage extends \JK\Elasticlog\Log\Message
{
    private $name;
    
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    
    public function toArray(): array
    {
        return [
            'foo' => 'bar',
            'name' => ucfirst($this->name)
        ];
    }
}
```


```php
$message = new MyCustomMessage();

// ... your code

$logger->log($message); 
```

> The duration is measured between creating and logging a message out of the box as a `duration` property.

## Available methods

```php
$message = (new Messages)->fooBarMessage();
$message->toArray(); // ['foo' => 'bar']

$message->add('a', (new Messages)->fooBarMessage());
$message->add('b', (new Messages)->barBazMessage());

$message->append((new Messages)->fooBarMessage());
$message->append((new Messages)->barBazMessage());

$message->merge([
    'x' => 'y',
]);

$this->assertEquals([
    'foo' => 'bar',
    'a'   => [
        'foo' => 'bar',
    ],
    'b'   => [
        'bar' => 'baz',
    ],
    'bar' => 'baz',
    'x'   => 'y',
], $message->build());
```



