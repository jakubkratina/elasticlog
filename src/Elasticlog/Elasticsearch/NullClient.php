<?php declare(strict_types=1);

namespace JK\Elasticlog\Elasticsearch;

use JK\Elasticlog\Contracts\Elasticsearch\Client as Contract;
use JK\Elasticlog\Contracts\Log\Message;

final class NullClient implements Contract
{
    /**
     * @param Message $message
     * @return array
     */
    public function log(Message $message): array
    {
        return [];
    }
}
