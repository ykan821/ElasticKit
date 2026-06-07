<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that groups documents into numeric value ranges.
 */
class Range extends Node
{
    protected $_key = 'range';

    /**
     * The numeric field to aggregate on.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * Array of range definitions for bucketing.
     *
     * @param array<string, mixed> $ranges
     * @return static
     */
    public function ranges($ranges)
    {
        return $this->addProperty('ranges', $ranges);
    }

    /**
     * Whether to return range buckets as a hash keyed by range key.
     *
     * @param bool $keyed
     * @return static
     */
    public function keyed($keyed)
    {
        return $this->addProperty('keyed', $keyed);
    }

    /**
     * Script to compute the bucket value.
     *
     * @param string|callable $script
     * @return static
     */
    public function script($script)
    {
        return $this->addProperty('script', $script);
    }

    /**
     * Value to use for documents missing the field value.
     *
     * @param float $missing
     * @return static
     */
    public function missing($missing)
    {
        return $this->addProperty('missing', $missing);
    }
}
