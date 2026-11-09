<?php

declare(strict_types=1);

namespace Tests;

use ElasticKit\Index\Bulk;
use ElasticKit\Index\Index;
use ElasticKit\Index\Support\ClientManager;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Support\FakeClient;

/**
 * Bulk flush contract without a cluster: the errors:true branches, buffer
 * lifecycle (preserve on abort, clear on success/handler return), and the
 * onError handoff's inherited request settings.
 */
final class BulkTest extends TestCase
{
    protected function tearDown(): void
    {
        ClientManager::reset();
    }

    public function testFlushSendsInterleavedActionsThenClears(): void
    {
        [$client, $bulk] = $this->makeBulk([['errors' => false]]);

        $response = $bulk->index('a', ['title' => 'a'])->flush();

        $this->assertSame(['errors' => false], $response);
        $this->assertSame(
            [['index' => ['_index' => 'products', '_id' => 'a']], ['title' => 'a']],
            $this->bulkCalls($client)[0]['body']
        );
        $this->assertSame([], $bulk->flush());
        $this->assertCount(1, $this->bulkCalls($client));
    }

    public function testFlushErrorWithoutHandlerThrowsAndPreservesBatchForRetry(): void
    {
        [$client, $bulk] = $this->makeBulk([
            ['errors' => true, 'items' => []],
            ['errors' => false, 'items' => []],
        ]);

        $bulk->index('a', ['title' => 'a'])->index('b', ['title' => 'b']);

        try {
            $bulk->flush();
            $this->fail('Expected a RuntimeException.');
        } catch (RuntimeException $e) {
            $this->assertStringStartsWith('Bulk request has errors:', $e->getMessage());
        }

        $bulk->flush();

        $calls = $this->bulkCalls($client);
        $this->assertCount(2, $calls);
        $this->assertSame($calls[0]['body'], $calls[1]['body']);
    }

    public function testHandlerConsumesBatchAndNewbulkInheritsRequestSettings(): void
    {
        [$client, $bulk] = $this->makeBulk([
            ['errors' => true, 'items' => []],
            ['errors' => false, 'items' => []],
        ]);

        $bulk->target('products_v2')->options(['refresh' => 'true'])->retryOnConflict(3)
            ->update('a', ['title' => 'a']);

        $received = [];
        $bulk->onError(function (array $response, array $body, Bulk $newbulk) use (&$received): void {
            $received = [$response, $body];
            $newbulk->update('a', $body[1]['doc'])->flush();
        })->flush();

        $this->assertSame(['errors' => true, 'items' => []], $received[0]);
        $this->assertSame(
            [
                ['update' => ['_index' => 'products_v2', '_id' => 'a', 'retry_on_conflict' => 3]],
                ['doc' => ['title' => 'a'], 'doc_as_upsert' => false],
            ],
            $received[1]
        );

        $retry = $this->bulkCalls($client)[1];
        $this->assertSame('true', $retry['refresh']);
        $this->assertSame('products_v2', $retry['body'][0]['update']['_index']);
        $this->assertSame(3, $retry['body'][0]['update']['retry_on_conflict']);

        $this->assertSame([], $bulk->flush());
        $this->assertCount(2, $this->bulkCalls($client));
    }

    public function testHandlerThrowAbortsFlushAndPreservesBatch(): void
    {
        [$client, $bulk] = $this->makeBulk([
            ['errors' => true, 'items' => []],
            ['errors' => false, 'items' => []],
        ]);

        $bulk->onError(fn () => throw new RuntimeException('handler abort'))
            ->index('a', ['title' => 'a']);

        try {
            $bulk->flush();
            $this->fail('Expected the handler exception to propagate.');
        } catch (RuntimeException $e) {
            $this->assertSame('handler abort', $e->getMessage());
        }

        $bulk->flush();

        $calls = $this->bulkCalls($client);
        $this->assertCount(2, $calls);
        $this->assertSame($calls[0]['body'], $calls[1]['body']);
    }

    public function testBatchSizeAutoFlushFiresExactlyAtThreshold(): void
    {
        [$client, $bulk] = $this->makeBulk([
            ['errors' => false],
            ['errors' => false],
        ]);

        $bulk->batchSize(2)->index('a', ['t' => 1]);
        $this->assertCount(0, $this->bulkCalls($client));

        $bulk->index('b', ['t' => 2]);
        $this->assertCount(1, $this->bulkCalls($client));

        $bulk->index('c', ['t' => 3]);
        $this->assertCount(1, $this->bulkCalls($client));

        $bulk->flush();
        $calls = $this->bulkCalls($client);
        $this->assertCount(2, $calls);
        $this->assertSame(
            [['index' => ['_index' => 'products', '_id' => 'c']], ['t' => 3]],
            $calls[1]['body']
        );
    }

    public function testFlushMergesInstanceAndCallOptions(): void
    {
        [$client, $bulk] = $this->makeBulk([['errors' => false]]);

        $bulk->options(['refresh' => 'true', 'timeout' => '10s'])->index('a', ['t' => 1]);
        $bulk->flush(['timeout' => '30s']);

        $params = $this->bulkCalls($client)[0];
        $this->assertSame('30s', $params['timeout']);
        $this->assertSame('true', $params['refresh']);
    }

    /**
     * @param array<int, array<string, mixed>> $bulkResponses queued responses for the bulk endpoint
     * @return array{0: FakeClient, 1: Bulk}
     */
    private function makeBulk(array $bulkResponses): array
    {
        $client = new FakeClient(['bulk' => $bulkResponses]);
        ClientManager::set($client);

        $index = new class ('products') extends Index {
            public function __construct(string $name)
            {
                $this->name = $name;
            }
        };

        return [$client, new Bulk($index)];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function bulkCalls(FakeClient $client): array
    {
        return array_map(
            static fn (array $call) => $call['params'],
            array_filter($client->calls, static fn (array $call) => $call['method'] === 'bulk')
        );
    }
}
