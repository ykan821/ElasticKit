<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that groups documents into IP address ranges.
 */
class IpRange extends Node
{
    protected $_key = 'ip_range';

    /**
     * The IP field to aggregate on.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * Array of IP range definitions for bucketing.
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
     * Value to use for documents missing the field value.
     *
     * @param mixed $missing
     * @return static
     */
    public function missing($missing)
    {
        return $this->addProperty('missing', $missing);
    }
}
