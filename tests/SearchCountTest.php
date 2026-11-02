<?php

declare(strict_types=1);

namespace Tests;

use Elastic\Elasticsearch\ClientInterface;
use ElasticKit\DSL\Query;
use ElasticKit\Index\Index;
use ElasticKit\Index\Support\ClientManager;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/**
 * Search::count() request shape — _count accepts a query in the body only,
 * so chained search params are projected away and routing is carried.
 */
final class SearchCountTest extends TestCase
{
    private FakeCountClient $client;

    private Index $index;

    protected function setUp(): void
    {
        $this->client = new FakeCountClient(['count' => [['count' => 2]]]);
        ClientManager::set($this->client);

        $this->index = new class extends Index {
            protected string $name = 'products';

            public function source(array $context = []): iterable
            {
                return [];
            }
        };
    }

    protected function tearDown(): void
    {
        ClientManager::reset();
    }

    public function testCountSendsQueryOnly(): void
    {
        $this->index->newQuery()
            ->term('status', 'published')
            ->size(10)
            ->sort('price', 'asc')
            ->highlight(['fields' => ['title' => new \stdClass()]])
            ->aggs('by_status', fn ($agg) => $agg->terms('status'))
            ->count();

        [$call] = $this->client->calls;
        $this->assertSame('count', $call['method']);
        $this->assertSame(['index', 'body'], array_keys($call['params']));
        $this->assertSame(['query'], array_keys($call['params']['body']));
    }

    public function testCountCarriesRouting(): void
    {
        $this->index->newQuery()->term('status', 'published')->routing('r1')->count();

        [$call] = $this->client->calls;
        $this->assertSame('r1', $call['params']['routing']);
        $this->assertSame(['query'], array_keys($call['params']['body']));
    }

    public function testCountWithoutClausesOmitsBody(): void
    {
        $this->index->newQuery()->count();

        [$call] = $this->client->calls;
        $this->assertSame(['index'], array_keys($call['params']));
    }

    public function testCountProjectsRawBodyDownToQuery(): void
    {
        $query = new Query([
            'query' => ['term' => ['status' => 'published']],
            'size' => 20,
            'sort' => [['price' => 'asc']],
        ]);

        $this->index->newQuery($query)->count();

        [$call] = $this->client->calls;
        $this->assertSame(['query'], array_keys($call['params']['body']));
        $this->assertSame(['term' => ['status' => 'published']], $call['params']['body']['query']);
    }
}

final class FakeCountClient implements ClientInterface
{
    /** @var array<int, array{method: string, params: array<string, mixed>}> */
    public array $calls = [];

    public function __construct(
        /** @var array<string, array<int, array<string, mixed>>> */
        private array $responses = []
    ) {
    }

    public function __call(string $method, array $args): FakeCountResult
    {
        $this->calls[] = ['method' => $method, 'params' => $args[0] ?? []];

        $queue = $this->responses[$method] ?? [];
        $body = array_shift($queue);
        $this->responses[$method] = $queue;

        return new FakeCountResult(is_array($body) ? $body : []);
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

final class FakeCountResult
{
    public function __construct(
        private readonly array $body
    ) {
    }

    /** @return array<string, mixed> */
    public function asArray(): array
    {
        return $this->body;
    }
}
