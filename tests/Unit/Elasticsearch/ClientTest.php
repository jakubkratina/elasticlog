<?php declare(strict_types=1);

namespace Tests\Unit\Elasticsearch;

use Carbon\Carbon;
use JK\Elasticlog\Contracts\Log\Message;
use JK\Elasticlog\Elasticsearch\Client;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Log\Messages;

final class ClientTest extends TestCase
{
    /** @test */
    public function it_creates_a_log_message(): void
    {
        $client = new Client(
            $this->mockElasticsearchClient(), 'elastic'
        );

        $client->log($this->buildLog());
    }

    /**
     * @return Message
     */
    private function buildLog(): Message
    {
        $log = (new Messages)->barBazMessage();

        $log->add('foo', (new Messages)->fooBarMessage());

        return $log;
    }

    /**
     * @return \Elasticsearch\Client
     * @throws \ReflectionException
     */
    private function mockElasticsearchClient(): \Elasticsearch\Client
    {
        $client = $this->createMock(\Elasticsearch\Client::class);

        $client->expects($this->once())
            ->method('index')
            ->with(
                $this->callback(function ($log) {
                    return $log['type'] === 'logs'
                        && $log['index'] === sprintf(
                            '%s-%s', 'elastic', Carbon::now()->format('Y.m.d')
                        )
                        && Carbon::parse($log['body']['datetime']) instanceof Carbon;
                })
            )
            ->willReturn([]);

        return $client;
    }
}
