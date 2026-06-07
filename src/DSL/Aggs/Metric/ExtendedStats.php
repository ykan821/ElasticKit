<?php

namespace ElasticKit\DSL\Aggs\Metric;

use ElasticKit\DSL\Node;

/**
 * A multi-value metrics aggregation that computes extended stats over numeric values.
 */
class ExtendedStats extends Node
{
    protected $_key = 'extended_stats';

    /**
     * The field to aggregate.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * (Optional) The value to use when the field is missing.
     *
     * @param mixed $missing
     * @return static
     */
    public function missing($missing)
    {
        return $this->addProperty('missing', $missing);
    }

    /**
     * (Optional) The script to use for the aggregation.
     *
     * @param string|callable $script
     * @return static
     */
    public function script($script)
    {
        return $this->addProperty('script', $script);
    }

    /**
     * Number of standard deviations above/below the mean to display.
     *
     * @param int $sigma
     * @return static
     */
    public function sigma($sigma)
    {
        return $this->addProperty('sigma', $sigma);
    }
}
