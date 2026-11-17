<?php

declare(strict_types=1);

namespace Tests;

use Elastic\Elasticsearch\Exception\ClientResponseException;
use ElasticKit\Index\Index;
use ElasticKit\Index\Rebuild;
use ElasticKit\Index\Support\ClientManager;
use ElasticKit\Index\Support\Event;
use ElasticKit\Index\Support\EventDispatcher;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Support\FakeClient;

/**
 * Rebuild failure cleanup — the swap catch must never delete a new index the
 * alias may already point to (lost-response ambiguity), while verified orphans
 * are removed and the original exception is never masked. Post-swap listener
 * failures never fail the call: they surface as an E_USER_WARNING and run()
 * returns the committed swap.
 */
final class RebuildTest extends TestCase
{
    protected function tearDown(): void
    {
        ClientManager::reset();
        EventDispatcher::reset();
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
        // Fresh-install path: a 404 proves the swap never landed.
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

    public function testAfterListenerFailureDoesNotFailCommittedRebuild(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['old_1']],
            'updateAliases' => [],
        ]);

        EventDispatcher::listen('rebuild.run.after', fn () => throw new RuntimeException('metrics down'));

        $warnings = [];
        set_error_handler(static function (int $errno, string $errstr) use (&$warnings): bool {
            $warnings[] = $errstr;
            return true;
        });

        try {
            $result = $rebuild->run();
        } finally {
            restore_error_handler();
        }

        $this->assertSame(['newIndex' => 'products_v2', 'oldIndex' => 'old_1'], $result);
        $this->assertCount(1, $warnings);
        $this->assertStringContainsString('succeeded', $warnings[0]);
        $this->assertStringContainsString('products_v2', $warnings[0]);
        $this->assertStringContainsString('metrics down', $warnings[0]);
        $this->assertDeletedIndices($client, []);
        $this->assertLockReleased($client);
    }

    public function testAfterListenerReceivesIndexesOnSuccess(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['old_1']],
            'updateAliases' => [],
        ]);

        $seen = null;
        EventDispatcher::listen('rebuild.run.after', function (Event $event) use (&$seen): void {
            $seen = $event;
        });

        $result = $rebuild->run();

        $this->assertSame('products_v2', $seen->newIndex);
        $this->assertSame('old_1', $seen->oldIndex);
        $this->assertSame(['newIndex' => 'products_v2', 'oldIndex' => 'old_1'], $result);
    }

    public function testBeforeListenerFailurePropagatesRaw(): void
    {
        EventDispatcher::listen('rebuild.run.before', fn () => throw new RuntimeException('before boom'));

        [, $rebuild] = $this->makeRebuild([]);

        try {
            $rebuild->run();
            $this->fail('Expected the raw before-listener exception.');
        } catch (RuntimeException $e) {
            $this->assertSame('before boom', $e->getMessage());
        }
    }

    public function testSwapCarriesAliasDefinition(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['old_1' => ['filter' => ['term' => ['tenant_id' => 7]]]]],
            'updateAliases' => [],
        ]);

        $rebuild->run();

        $this->assertSame(
            ['index' => 'products_v2', 'alias' => 'products', 'filter' => ['term' => ['tenant_id' => 7]]],
            $this->aliasActions($client)[1]['add']
        );
    }

    public function testSwapInheritsWriteIndexAcrossBackings(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['old_1' => [], 'old_2' => ['is_write_index' => true]]],
            'updateAliases' => [],
        ]);

        $rebuild->run();

        $this->assertSame(
            ['index' => 'products_v2', 'alias' => 'products', 'is_write_index' => true],
            $this->aliasActions($client)[2]['add']
        );
    }

    public function testConflictingAliasDefinitionsAbortBeforeImport(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'existsAlias' => [true],
            'getAlias' => [['v1' => ['filter' => ['a' => 1]], 'v2' => ['filter' => ['b' => 2]]]],
        ]);

        try {
            $rebuild->run();
            $this->fail('Expected a conflicting-definitions RuntimeException.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('different definitions', $e->getMessage());
        }

        $created = array_filter($client->indices->calls, fn (array $call) => $call['method'] === 'create');
        $this->assertSame([], array_values($created));
        $this->assertDeletedIndices($client, []);
    }

    public function testRollbackCarriesAliasDefinition(): void
    {
        [$client, $rebuild] = $this->makeRebuild([
            'getAlias' => [['products_v2' => ['filter' => ['term' => ['tenant_id' => 7]]]]],
            'exists' => [true],
            'updateAliases' => [],
        ]);

        $from = $rebuild->rollback('old_1');

        $this->assertSame('products_v2', $from);
        $this->assertSame(
            ['index' => 'old_1', 'alias' => 'products', 'filter' => ['term' => ['tenant_id' => 7]]],
            $this->aliasActions($client)[1]['add']
        );
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

    private function assertLockReleased(FakeClient $client): void
    {
        $released = array_filter(
            $client->calls,
            fn (array $call) => $call['method'] === 'delete' && ($call['params']['index'] ?? null) === '.ek_locks'
        );

        $this->assertNotEmpty($released, 'Expected the rebuild lock to be released.');
    }

    /**
     * @return array<int, array<string, mixed>> the updateAliases actions
     */
    private function aliasActions(FakeClient $client): array
    {
        foreach ($client->indices->calls as $call) {
            if ($call['method'] === 'updateAliases') {
                return $call['params']['body']['actions'];
            }
        }

        return [];
    }
}
