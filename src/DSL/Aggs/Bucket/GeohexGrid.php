<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that groups documents into H3 hexagonal grid cells.
 */
class GeohexGrid extends Node
{
    protected $_key = 'geohex_grid';

    /**
     * The geo point field to aggregate on.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * H3 resolution for grid cells.
     *
     * @param int $precision
     * @return static
     */
    public function precision($precision)
    {
        return $this->addProperty('precision', $precision);
    }

    /**
     * Maximum number of hex buckets to return.
     *
     * @param int $size
     * @return static
     */
    public function size($size)
    {
        return $this->addProperty('size', $size);
    }

    /**
     * Number of hex buckets to return from each shard.
     *
     * @param int $shardSize
     * @return static
     */
    public function shardSize($shardSize)
    {
        return $this->addProperty('shard_size', $shardSize);
    }
}
