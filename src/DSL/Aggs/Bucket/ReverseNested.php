<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that aggregates on parent documents from within a nested aggregation.
 */
class ReverseNested extends Node
{
    protected $_key = 'reverse_nested';

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        if (empty($this->_properties)) {
            return (object)[];
        }
        return parent::toArray();
    }

    /**
     * Path to the nested object to reverse out of.
     *
     * @param string $path
     * @return static
     */
    public function path($path)
    {
        return $this->addProperty('path', $path);
    }
}
