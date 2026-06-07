<?php

namespace ElasticKit\DSL\Aggs\Pipeline;

use ElasticKit\DSL\Node;

/**
 * A pipeline aggregation that computes the average of a specified metric in a sibling aggregation.
 */
class AvgBucket extends Node
{
    protected $_key = 'avg_bucket';

    /**
     * Path to the buckets to average.
     *
     * @param string $path
     * @return static
     */
    public function bucketsPath($path)
    {
        return $this->addProperty('buckets_path', $path);
    }

    /**
     * Policy to apply when gaps are found in the data.
     *
     * @param string $policy
     * @return static
     */
    public function gapPolicy($policy)
    {
        return $this->addProperty('gap_policy', $policy);
    }

    /**
     * Format for the output value.
     *
     * @param string $format
     * @return static
     */
    public function format($format)
    {
        return $this->addProperty('format', $format);
    }

    /**
     * (Optional) The value to use when the aggregation is missing a value.
     *
     * @param mixed $missing
     * @return static
     */
    public function missing($missing)
    {
        return $this->addProperty('missing', $missing);
    }
}
