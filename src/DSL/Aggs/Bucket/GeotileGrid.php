<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that groups documents into geotile grid cells.
 */
class GeotileGrid extends Node
{
    protected $_key = 'geotile_grid';

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
     * Zoom level (precision) for geotile grid cells.
     *
     * @param int $precision
     * @return static
     */
    public function precision($precision)
    {
        return $this->addProperty('precision', $precision);
    }

    /**
     * Maximum number of geotile buckets to return.
     *
     * @param int $size
     * @return static
     */
    public function size($size)
    {
        return $this->addProperty('size', $size);
    }

    /**
     * Number of geotile buckets to return from each shard.
     *
     * @param int $shardSize
     * @return static
     */
    public function shardSize($shardSize)
    {
        return $this->addProperty('shard_size', $shardSize);
    }
}
