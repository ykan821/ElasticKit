<?php

declare(strict_types=1);

namespace Tests\Support;

use Elastic\Elasticsearch\ClientInterface;
use Psr\Http\Message\RequestInterface;

/**
 * In-memory Elasticsearch client double: records every endpoint call and
 * returns queued responses (a queued Throwable is thrown). indices() is a
 * FakeIndicesNamespace with its own queues. For request-shape assertions
 * and failure paths a real cluster cannot reproduce.
 */
final class FakeClient implements ClientInterface
{
    /** @var array<int, array{method: string, params: array<string, mixed>}> */
    public array $calls = [];

    /** @var array<string, array<int, mixed>> */
    private array $script;

    public FakeIndicesNamespace $indices;

    public function __construct(
        /** @var array<string, array<int, mixed>> per-method outcome queue for client endpoints */
        array $script = [],
        /** @var array<string, array<int, mixed>> per-method outcome queue for indices endpoints */
        array $indicesScript = []
    ) {
        $this->indices = new FakeIndicesNamespace($indicesScript);
        $this->script = $script;
    }

    public function __call(string $method, array $args): mixed
    {
        $this->calls[] = ['method' => $method, 'params' => $args[0] ?? []];

        if ($method === 'indices') {
            return $this->indices;
        }

        $next = $this->shift($method, $this->script);
        if ($next instanceof \Throwable) {
            throw $next;
        }

        return new FakeResult(true, is_array($next) ? $next : []);
    }

    /**
     * @param array<string, array<int, mixed>> $script
     */
    private function shift(string $method, array &$script): mixed
    {
        $queue = $script[$method] ?? [];
        $next = array_shift($queue);
        $script[$method] = $queue;

        return $next;
    }

    public function getTransport(): \Elastic\Transport\Transport
    {
        throw new \LogicException('unused');
    }

    public function getLogger(): \Psr\Log\LoggerInterface
    {
        throw new \LogicException('unused');
    }

    public function setAsync(bool $async): ClientInterface
    {
        return $this;
    }

    public function getAsync(): bool
    {
        return false;
    }

    public function setElasticMetaHeader(bool $active): ClientInterface
    {
        return $this;
    }

    public function getElasticMetaHeader(): bool
    {
        return true;
    }

    public function setResponseException(bool $active): ClientInterface
    {
        return $this;
    }

    public function getResponseException(): bool
    {
        return true;
    }

    public function sendRequest(RequestInterface $request)
    {
        throw new \LogicException('unused');
    }
}
