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
```php
/** @var Client $logger */ 
$logger->log(new CronTaskPing('message'));
```
