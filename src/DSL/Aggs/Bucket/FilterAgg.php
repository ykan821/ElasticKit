<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Query;
use ElasticKit\DSL\Node;

/**
 * A single bucket aggregation that limits documents matching a query.
 */
class FilterAgg extends Node
{
    protected $_key = 'filter';

    /**
     * (Required) The filter query to apply.
     *
     * @param mixed $filter
     * @return static
     */
    public function setFilter($filter)
    {
        $this->_properties = $filter;
        return $this;
    }

    /**
     * Serialize to an Elasticsearch DSL array.
     *
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return Query::create($this->_properties)->toArray()['query'];
    }
}
