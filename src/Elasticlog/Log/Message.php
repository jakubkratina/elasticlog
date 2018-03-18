<?php declare(strict_types=1);

namespace JK\Elasticlog\Log;

use Carbon\Carbon;
use JK\Elasticlog\Contracts\Log\Message as MessageContract;
use JK\Elasticlog\Stopwatch\Stopwatch;

abstract class Message implements MessageContract
{
    /**
     * @var array
     */
    protected $log = [];

    /**
     * @var Stopwatch
     */
    protected $stopwatch;

    /**
     * @var string|null
     */
    protected $section;

    /**
     * @var Carbon|null
     */
    protected $timestamp;

    public function __construct()
    {
        $this->stopwatch()->start();
    }

    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return string|null
     */
    public function section(): ?string
    {
        return $this->section;
    }

    /**
     * @return Carbon|null
     */
    public function timestamp(): ?Carbon
    {
        return $this->timestamp;
    }

    /**
     * @return array
     */
    public function build(): array
    {
        return array_merge($this->toArray(), $this->log());
    }

    /**
     * @param string $key
     * @param MessageContract $log
     */
    public function add(string $key, MessageContract $log): void
    {
        $this->log[$key] = $log->toArray();
    }

    /**
     * @param array $array
     */
    public function merge(array $array): void
    {
        $this->log = array_merge($this->log(), $array);
    }

    /**
     * @param MessageContract $log
     */
    public function append(MessageContract $log): void
    {
        $this->log = array_merge($this->log(), $log->toArray());
    }

    /**
     * @return Stopwatch
     */
    public function stopwatch(): Stopwatch
    {
        if ($this->stopwatch === null) {
            $this->stopwatch = new Stopwatch(spl_object_hash($this));
        }

        return $this->stopwatch;
    }

    /**
     * @param Carbon $timestamp
     * @return MessageContract
     */
    public function setTimestamp(Carbon $timestamp): MessageContract
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return array
     */
    protected function log(): array
    {
        return $this->log;
    }
}
