<?php declare(strict_types=1);

namespace Tests\Unit\Log;

use PHPUnit\Framework\TestCase;

final class CompositeLoggerTest extends TestCase
{
    /** @test */
    public function it_returns_a_simple_log(): void
    {
        $message = (new Messages)->fooBarMessage();

        $message->add('a', (new Messages)->fooBarMessage());
        $message->add('b', (new Messages)->barBazMessage());

        $message->append((new Messages)->fooBarMessage());
        $message->append((new Messages)->barBazMessage());

        $message->merge([
            'x' => 'y',
        ]);

        $this->assertEquals([
            'foo' => 'bar',
            'a'   => [
                'foo' => 'bar',
            ],
            'b'   => [
                'bar' => 'baz',
            ],
            'bar' => 'baz',
            'x'   => 'y',
        ], $message->build());
    }
}
