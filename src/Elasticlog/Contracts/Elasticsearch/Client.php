<?php declare(strict_types=1);

namespace JK\Elasticlog\Contracts\Elasticsearch;

use JK\Elasticlog\Contracts\Log\Message;

interface Client
{
    /**
     * @param Message $message
     * @return array
     */
    public function log(Message $message): array;

    /**
     * @param string $key
     */
    public function setDatetimeKey(string $key): void;
}
