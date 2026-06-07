<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

class VariableWidthHistogram extends Node
{
    protected $_key = 'variable_width_histogram';

    /**
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * @param int $buckets
     * @return static
     */
    public function buckets($buckets)
    {
        return $this->addProperty('buckets', $buckets);
    }

    /**
     * @param int $shardBuckets
     * @return static
     */
    public function shardBuckets($shardBuckets)
    {
        return $this->addProperty('shard_buckets', $shardBuckets);
    }
}
