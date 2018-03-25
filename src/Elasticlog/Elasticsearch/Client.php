<?php declare(strict_types=1);

namespace JK\Elasticlog\Elasticsearch;

use Adbar\Dot;
use Carbon\Carbon;
use Elasticsearch\Client as Elasticsearch;
use JK\Elasticlog\Contracts\Elasticsearch\Client as Contract;
use JK\Elasticlog\Contracts\Log\Message;

final class Client implements Contract
{
    /**
     * @var Elasticsearch
     */
    private $es;

    /**
     * @var string
     */
    private $index;

    /**
     * @var string
     */
    private $datetimeKey = 'datetime';

    /**
     * @param Elasticsearch $es
     * @param string $index
     */
    public function __construct(Elasticsearch $es, string $index)
    {
        $this->es = $es;
        $this->index = $index;
    }

    /**
     * @param Message $message
     * @return array
     */
    public function log(Message $message): array // return response object?
    {
        if ($message->stopwatch()->isRunning()) {
            $message->stopwatch()->stop();
        }

        $log = [
            'body'  => $this->body($message),
            'type'  => 'logs',
            'index' => $this->index(),
        ];

        return $this->es->index($log);
    }

    /**
     * @return string
     */
    private function index(): string
    {
        return sprintf('%s-%s', $this->index, Carbon::now()->format('Y.m.d'));
    }

    /**
     * @param Message $message
     * @return array
     */
    private function timestamp(Message $message): array
    {
        return [
            $this->datetimeKey => $message->timestamp() !== null
                ? $message->timestamp()->toIso8601String()
                : Carbon::now()->toIso8601String(),
        ];
    }

    /**
     * @param Message $message
     * @return array
     */
    private function body(Message $message): array
    {
        return array_merge(
            $this->data($message), $this->meta($message)
        );
    }

    /**
     * @param Message $message
     * @return string
     */
    private function section(Message $message): string
    {
        return $message->section() ?: $this->resolveSectionFromClassName($message);
    }

    /**
     * @param Message $message
     * @return string
     */
    private function resolveSectionFromClassName(Message $message): string
    {
        return str_replace('\\', '_', strtolower(\get_class($message)));
    }

    /**
     * @param Message $message
     * @return array
     */
    private function meta(Message $message): array
    {
        return array_merge([
            'section'  => $this->section($message),
            'duration' => $message->stopwatch()->duration(),
        ], $this->timestamp($message));
    }

    /**
     * @param Message $message
     * @return array
     */
    private function data(Message $message): array
    {
        return (new Dot([
            'data' => [$this->section($message) => $message->build()]
        ]))->all();
    }
}
