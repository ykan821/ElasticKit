<?php

declare(strict_types=1);

namespace Tests;

use Elastic\Elasticsearch\Exception\ClientResponseException;
use ElasticKit\Index\Index;
use ElasticKit\Index\Rebuild;
use ElasticKit\Index\Support\ClientManager;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Support\FakeClient;

/**
 * Rebuild failure cleanup — the swap catch must never delete a new index the
 * alias may already point to (lost-response ambiguity), while verified orphans
 * are removed and the original exception is never masked.
 */
final class RebuildCleanupTest extends TestCase
{
    protected function tearDown(): void
    {
        ClientManager::reset();
    }

    public function testSuccessfulSwapKeepsNewIndex(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['old_1']],
            'updateAliases' => [],
        ]);

        $result = $rebuild->run();

        $this->assertSame('products_v2', $result['newIndex']);
        $this->assertSame('old_1', $result['oldIndex']);
        $this->assertNotContains('delete', array_column($client->indices->calls, 'method'));
    }

    public function testSwapFailureWithAliasStillOnOldIndexDeletesOrphan(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['old_1'], ['old_1']],
            'updateAliases' => [new RuntimeException('response lost')],
        ]);

        try {
            $rebuild->run();
            $this->fail('Expected the original swap exception to propagate.');
        } catch (RuntimeException $e) {
            $this->assertSame('response lost', $e->getMessage());
        }

        $this->assertDeletedIndices($client, ['products_v2']);
    }

    public function testSwapFailureAfterAliasSwitchKeepsNewIndex(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['old_1'], ['products_v2']],
            'updateAliases' => [new RuntimeException('response lost')],
        ]);

        try {
            $rebuild->run();
            $this->fail('Expected a kept-index RuntimeException.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('already points to', $e->getMessage());
            $this->assertStringContainsString('rollback()', $e->getMessage());
            $this->assertSame('response lost', $e->getPrevious()?->getMessage());
        }

        $this->assertDeletedIndices($client, []);
    }

    public function testSwapFailureWithUnverifiableAliasKeepsNewIndex(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['old_1'], new RuntimeException('cluster unreachable')],
            'updateAliases' => [new RuntimeException('response lost')],
        ]);

        try {
            $rebuild->run();
            $this->fail('Expected a kept-index RuntimeException.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('could not be verified', $e->getMessage());
            $this->assertSame('response lost', $e->getPrevious()?->getMessage());
        }

        $this->assertDeletedIndices($client, []);
    }

    public function testPutAliasFailureWithMissingAliasDeletesOrphan(): void
    {
        // Alias does not exist yet (fresh install path); the 404 verifies the
        // swap never landed, so the orphan is safe to delete.
        $notFound = new ClientResponseException();
        $notFound->setResponse(new Response(404));

        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [false],
            'exists' => [false, false],
            'putAlias' => [new RuntimeException('response lost')],
            'getAlias' => [$notFound],
        ]);

        try {
            $rebuild->run();
            $this->fail('Expected the original swap exception to propagate.');
        } catch (RuntimeException $e) {
            $this->assertSame('response lost', $e->getMessage());
        }

        $this->assertDeletedIndices($client, ['products_v2']);
    }

    public function testImportFailureDeletesOrphanWithoutMasking(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'delete' => [new RuntimeException('cleanup unreachable')],
        ], fn () => throw new RuntimeException('source boom'));

        try {
            $rebuild->run();
            $this->fail('Expected the source exception to propagate.');
        } catch (RuntimeException $e) {
            $this->assertSame('source boom', $e->getMessage());
        }

        $this->assertDeletedIndices($client, ['products_v2']);
    }

    /**
     * @param array<string, array<int, mixed>> $indicesScript per-endpoint outcome queue for indices()
     * @param (callable(array<string, mixed>): iterable)|null $source
     * @return array{0: FakeClient, 1: Rebuild}
     */
    private function makeRebuild(array $indicesScript, ?callable $source = null): array
    {
        $client = new FakeClient([], $indicesScript);
        ClientManager::set($client);

        $index = new class ('products', $source) extends Index {
            public function __construct(
                string $name,
                private readonly ?\Closure $source
            ) {
                $this->name = $name;
            }

            public function rebuildName(): string
            {
                return $this->name . '_v2';
            }

            public function source(array $context = []): iterable
            {
                return $this->source !== null ? ($this->source)($context) : [];
            }
        };

        return [$client, (new Rebuild($index))->allowEmpty()];
    }

    /**
     * @param array<int, string> $expected index names deleted via indices()->delete()
     */
    private function assertDeletedIndices(FakeClient $client, array $expected): void
    {
        $deleted = array_map(
            fn (array $call) => $call['params']['index'] ?? null,
            array_filter($client->indices->calls, fn (array $call) => $call['method'] === 'delete')
        );

        $this->assertSame($expected, array_values(array_filter($deleted)));
    }
}
