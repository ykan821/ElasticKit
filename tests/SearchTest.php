<?php

declare(strict_types=1);

namespace Tests;

use ElasticKit\DSL\Query;
use ElasticKit\Index\Index;
use ElasticKit\Index\Support\ClientManager;
use PHPUnit\Framework\TestCase;
use Tests\Support\FakeClient;

/**
 * Search request assembly, asserted on wire params captured by the fake client.
 */
final class SearchTest extends TestCase
{
    private Index $index;

    protected function setUp(): void
    {
        $this->index = new class () extends Index {
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
        $client = $this->client(['count' => [['count' => 2]]]);

        $this->index->newQuery()
            ->term('status', 'published')
            ->size(10)
            ->sort('price', 'asc')
            ->highlight(['fields' => ['title' => new \stdClass()]])
            ->aggs('by_status', fn ($agg) => $agg->terms('status'))
            ->count();

        [$call] = $client->calls;
        $this->assertSame('count', $call['method']);
        $this->assertSame(['index', 'body'], array_keys($call['params']));
        $this->assertSame(['query'], array_keys($call['params']['body']));
    }

    public function testCountCarriesRouting(): void
    {
        $client = $this->client(['count' => [['count' => 2]]]);

        $this->index->newQuery()->term('status', 'published')->routing('r1')->count();

        [$call] = $client->calls;
        $this->assertSame('r1', $call['params']['routing']);
        $this->assertSame(['query'], array_keys($call['params']['body']));
    }

    public function testCountWithoutClausesOmitsBody(): void
    {
        $client = $this->client(['count' => [['count' => 2]]]);

        $this->index->newQuery()->count();

        [$call] = $client->calls;
        $this->assertSame(['index'], array_keys($call['params']));
    }

    public function testCountProjectsRawBodyDownToQuery(): void
    {
        $client = $this->client(['count' => [['count' => 2]]]);
        $query = new Query([
            'query' => ['term' => ['status' => 'published']],
            'size' => 20,
            'sort' => [['price' => 'asc']],
        ]);

        $this->index->newQuery($query)->count();

        [$call] = $client->calls;
        $this->assertSame(['query'], array_keys($call['params']['body']));
        $this->assertSame(['term' => ['status' => 'published']], $call['params']['body']['query']);
    }

    public function testGetAppliesIndexTrackTotalHitsDefault(): void
    {
        $client = $this->client(['search' => [['hits' => ['hits' => []]]]]);

        $this->index->newQuery()->term('status', 'published')->get();

        [$call] = $client->calls;
        $this->assertFalse($call['params']['body']['track_total_hits']);
    }

    public function testGetPreservesRawTrackTotalHits(): void
    {
        $client = $this->client(['search' => [['hits' => ['hits' => []]]]]);
        $query = new Query([
            'query' => ['term' => ['status' => 'published']],
            'track_total_hits' => true,
        ]);

        $this->index->newQuery($query)->get();

        [$call] = $client->calls;
        $this->assertTrue($call['params']['body']['track_total_hits']);
    }

    public function testGetLeavesQueryCleanForLaterScroll(): void
    {
        $client = $this->client(['search' => [
            ['hits' => ['hits' => []]],
            ['hits' => ['hits' => []], '_scroll_id' => 'sid'],
        ]]);

        $search = $this->index->newQuery()->term('status', 'published');
        $search->get();
        $search->scroll();

        [$get, $scrollStart] = $client->calls;
        $this->assertFalse($get['params']['body']['track_total_hits']);
        $this->assertArrayNotHasKey('track_total_hits', $scrollStart['params']['body']);
    }

    public function testScrollAppliesSizeDefaultWhenAbsent(): void
    {
        $client = $this->client(['search' => [['hits' => ['hits' => []], '_scroll_id' => 'sid']]]);

        $this->index->newQuery()->term('status', 'published')->scroll();

        [$call] = $client->calls;
        $this->assertSame(1000, $call['params']['body']['size']);
        $this->assertArrayNotHasKey('track_total_hits', $call['params']['body']);
    }

    public function testScrollKeepsRawBodySize(): void
    {
        $client = $this->client(['search' => [['hits' => ['hits' => []], '_scroll_id' => 'sid']]]);
        $query = new Query([
            'query' => ['term' => ['status' => 'published']],
            'size' => 20,
        ]);

        $this->index->newQuery($query)->scroll();

        [$call] = $client->calls;
        $this->assertSame(20, $call['params']['body']['size']);
    }

    /**
     * @param array<string, array<int, mixed>> $script
     */
    private function client(array $script): FakeClient
    {
        $client = new FakeClient($script);
        ClientManager::set($client);

        return $client;
    }
}
