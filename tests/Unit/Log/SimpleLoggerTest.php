<?php declare(strict_types=1);

namespace Tests\Unit\Log;

use PHPUnit\Framework\TestCase;

final class SimpleLoggerTest extends TestCase
{
    /** @test */
    public function it_returns_a_simple_log(): void
    {
        $message = (new Messages)->fooBarMessage();

        $this->assertSame([
            'foo' => 'bar',
        ], $message->toArray());
    }
}
