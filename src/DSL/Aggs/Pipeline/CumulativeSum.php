<?php

namespace ElasticKit\DSL\Aggs\Pipeline;

use ElasticKit\DSL\Node;

/**
 * A pipeline aggregation that calculates the cumulative sum of a specified metric in a parent histogram.
 */
class CumulativeSum extends Node
{
    protected $_key = 'cumulative_sum';

    /**
     * Path to the buckets to cumulatively sum.
     *
     * @param string $path
     * @return static
     */
    public function bucketsPath($path)
    {
        return $this->addProperty('buckets_path', $path);
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
}
