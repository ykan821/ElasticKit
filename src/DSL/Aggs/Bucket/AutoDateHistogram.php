<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that automatically determines bucket intervals for date values.
 */
class AutoDateHistogram extends Node
{
    protected $_key = 'auto_date_histogram';

    /**
     * Target number of buckets to return.
     *
     * @param int $buckets
     * @return static
     */
    public function buckets($buckets)
    {
        return $this->addProperty('buckets', $buckets);
    }

    /**
     * Date format pattern for bucket keys.
     *
     * @param string $format
     * @return static
     */
    public function format($format)
    {
        return $this->addProperty('format', $format);
    }

    /**
     * Time zone for bucketing.
     *
     * @param string $timeZone
     * @return static
     */
    public function timeZone($timeZone)
    {
        return $this->addProperty('time_zone', $timeZone);
    }

    /**
     * Minimum interval to use when automatically determining buckets.
     *
     * @param string $minimumInterval
     * @return static
     */
    public function minimumInterval($minimumInterval)
    {
        return $this->addProperty('minimum_interval', $minimumInterval);
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
