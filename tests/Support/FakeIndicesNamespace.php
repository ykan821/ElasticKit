<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * In-memory indices() namespace double. Outcome queues per endpoint:
 * bool for exists/existsAlias, backing index names or an index => definition
 * map for getAlias, Throwable to throw. Missing entries fall back to a safe
 * ok/true result.
 */
final class FakeIndicesNamespace
{
    /** @var array<int, array{method: string, params: array<string, mixed>}> */
    public array $calls = [];

    /** @var array<string, array<int, mixed>> */
    private array $script;

    public function __construct(
        array $script = []
    ) {
        $this->script = $script;
    }

    public function __call(string $method, array $args): FakeResult
    {
        $this->calls[] = ['method' => $method, 'params' => $args[0] ?? []];

        $queue = $this->script[$method] ?? [];
        $next = array_shift($queue);
        $this->script[$method] = $queue;
        if ($next instanceof \Throwable) {
            throw $next;
        }

        if ($method === 'getAlias') {
            $alias = $args[0]['name'] ?? '';
            $backing = [];
            foreach ((array) $next as $index => $definition) {
                if (is_array($definition)) {
                    $backing[$index] = ['aliases' => [$alias => $definition]];
                } else {
                    $backing[$definition] = ['aliases' => []];
                }
            }
            return new FakeResult(true, $backing);
        }

        return new FakeResult(is_bool($next) ? $next : true);
    }
}
