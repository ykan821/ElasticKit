<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use stdClass;
use ElasticKit\DSL\Node;

/**
 * A single bucket aggregation that defines all documents within the search context.
 */
class GlobalAgg extends Node
{
    protected $_key = 'global';

    /**
     * Serialize to an Elasticsearch DSL array.
     *
     * @return stdClass
     */
    public function toArray()
    {
        return new stdClass();
    }
}
