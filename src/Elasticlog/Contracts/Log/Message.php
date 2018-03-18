<?php declare(strict_types=1);

namespace JK\Elasticlog\Contracts\Log;

use JK\Elasticlog\Stopwatch\Stopwatch;

interface Message
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return array
     */
    public function build(): array;

    /**
     * @param string  $key
     * @param Message $log
     */
    public function add(string $key, Message $log): void;

    /**
     * @param array $array
     */
    public function merge(array $array): void;

    /**
     * @param Message $log
     */
    public function append(Message $log): void;

    /**
     * @return Stopwatch
     */
    public function stopwatch(): Stopwatch;
}
