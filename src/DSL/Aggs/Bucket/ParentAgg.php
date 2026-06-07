<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that aggregates on parent documents from a join field.
 */
class ParentAgg extends Node
{
    protected $_key = 'parent';

    /**
     * The child type that identifies the parent documents to aggregate on.
     *
     * @param string $type
     * @return static
     */
    public function type($type)
    {
        return $this->addProperty('type', $type);
    }
}
