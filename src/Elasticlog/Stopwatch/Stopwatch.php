<?php declare(strict_types=1);

namespace JK\Elasticlog\Stopwatch;

use Symfony\Component\Stopwatch\Stopwatch as Watch;

final class Stopwatch
{
    /**
     * @var Watch
     */
    private $watch;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $duration = 0;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->watch = new Watch();
        $this->name = $name;
    }

    public function start(): void
    {
        $this->duration = 0;

        $this->watch->start($this->name);
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->watch->isStarted($this->name);
    }

    public function stop(): void
    {
        $this->duration = $this->watch->stop($this->name)->getDuration();
    }

    /**
     * @return float
     */
    public function duration(): float
    {
        return $this->duration;
    }
}
