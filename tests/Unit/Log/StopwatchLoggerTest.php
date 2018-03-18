<?php declare(strict_types=1);

namespace Tests\Unit\Log;

use PHPUnit\Framework\TestCase;

final class StopwatchLoggerTest extends TestCase
{
    /** @test */
    public function it_measure_time_with_default_event_name(): void
    {
        $message = (new Messages)->fooBarMessage();

        $message->stopwatch()->start();

        usleep(100000);

        $message->stopwatch()->stop();

        $this->assertGreaterThanOrEqual(100, $message->stopwatch()->duration());
    }

    /** @test */
    public function it_measure_time_with_defined_event_name(): void
    {
        $message = (new Messages)->fooBarMessage();

        $message->stopwatch()->start();

        usleep(100000);

        $message->stopwatch()->stop();

        $this->assertGreaterThanOrEqual(100, $message->stopwatch()->duration());
    }
}
