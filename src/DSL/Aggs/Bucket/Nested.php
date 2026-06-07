<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that aggregates on nested document fields.
 */
class Nested extends Node
{
    protected $_key = 'nested';

    /**
     * Path to the nested object to aggregate on.
     *
     * @param string $path
     * @return static
     */
    public function path($path)
    {
        return $this->addProperty('path', $path);
    }

    /**
     * Whether to return an empty bucket instead of an error for unmapped nested types.
     *
     * @param bool $ignoreUnmapped
     * @return static
     */
    public function ignoreUnmapped($ignoreUnmapped)
    {
        return $this->addProperty('ignore_unmapped', $ignoreUnmapped);
    }
}
