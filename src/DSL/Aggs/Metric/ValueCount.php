<?php

namespace ElasticKit\DSL\Aggs\Metric;

use ElasticKit\DSL\Node;

/**
 * A single-value metrics aggregation that counts the number of values extracted from documents.
 */
class ValueCount extends Node
{
    protected $_key = 'value_count';

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
     * (Optional) The script to use for the aggregation.
     *
     * @param string|callable $script
     * @return static
     */
    public function script($script)
    {
        return $this->addProperty('script', $script);
    }
}
