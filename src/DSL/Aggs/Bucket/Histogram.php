<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that groups documents into numeric value intervals.
 */
class Histogram extends Node
{
    protected $_key = 'histogram';

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
     * Interval size for each bucket.
     *
     * @param float $interval
     * @return static
     */
    public function interval($interval)
    {
        return $this->addProperty('interval', $interval);
    }

    /**
     * Minimum number of documents in a bucket to be returned.
     *
     * @param int $minDocCount
     * @return static
     */
    public function minDocCount($minDocCount)
    {
        return $this->addProperty('min_doc_count', $minDocCount);
    }

    /**
     * Extends the bucket range beyond the data bounds.
     *
     * @param mixed $bounds
     * @return static
     */
    public function extendedBounds($bounds)
    {
        return $this->addProperty('extended_bounds', $bounds);
    }

    /**
     * Sort order for buckets.
     *
     * @param mixed $order
     * @return static
     */
    public function order($order)
    {
        return $this->addProperty('order', $order);
    }

    /**
     * Whether to return bucket keys as strings.
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
     * @param float $missing
     * @return static
     */
    public function missing($missing)
    {
        return $this->addProperty('missing', $missing);
    }

    /**
     * Format pattern for bucket key values.
     *
     * @param string $format
     * @return static
     */
    public function format($format)
    {
        return $this->addProperty('format', $format);
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
     * Offset for bucket starting values.
     *
     * @param float $offset
     * @return static
     */
    public function offset($offset)
    {
        return $this->addProperty('offset', $offset);
    }

    /**
     * Limits the bucket range to a bounded range.
     *
     * @param mixed $hardBounds
     * @return static
     */
    public function hardBounds($hardBounds)
    {
        return $this->addProperty('hard_bounds', $hardBounds);
    }
}
