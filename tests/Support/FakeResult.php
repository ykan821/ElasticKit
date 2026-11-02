<?php

declare(strict_types=1);

namespace Tests\Support;

/**
 * Scripted endpoint result: asBool() for exists/existsAlias, asArray() for
 * everything else (getAlias keys are backing index names).
 */
final class FakeResult
{
    public function __construct(
        private readonly bool $bool,
        private readonly array $array = []
    ) {
    }

    public function asBool(): bool
    {
        return $this->bool;
    }

    /** @return array<string, mixed> */
    public function asArray(): array
    {
        return $this->array;
    }
}
