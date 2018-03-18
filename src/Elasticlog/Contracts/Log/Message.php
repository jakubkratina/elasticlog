<?php declare(strict_types=1);

namespace JK\Elasticlog\Contracts\Log;

use Carbon\Carbon;
use JK\Elasticlog\Stopwatch\Stopwatch;

interface Message
{
    /**
     * @return string|null
     */
    public function section(): ?string;

    /**
     * @return Carbon|null
     */
    public function timestamp(): ?Carbon;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return array
     */
    public function build(): array;

    /**
     * @param string $key
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

    /**
     * @param Carbon $timestamp
     * @return Message
     */
    public function setTimestamp(Carbon $timestamp): Message;
}
