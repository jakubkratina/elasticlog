<?php declare(strict_types=1);

namespace Tests\Unit\Log;

use JK\Elasticlog\Contracts\Log\Message as MessageContract;
use JK\Elasticlog\Log\Message;

final class Messages
{
    /**
     * @return Message
     */
    public function fooBarMessage(): Message
    {
        return new class extends Message implements MessageContract
        {
            /**
             * @return array
             */
            public function toArray(): array
            {
                return [
                    'foo' => 'bar',
                ];
            }
        };
    }

    /**
     * @return Message
     */
    public function barBazMessage(): Message
    {
        return new class extends Message implements MessageContract
        {
            /**
             * @return array
             */
            public function toArray(): array
            {
                return [
                    'bar' => 'baz',
                ];
            }
        };
    }
}
