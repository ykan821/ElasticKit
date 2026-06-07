<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

class MultiTerms extends Node
{
    protected $_key = 'multi_terms';

    /**
     * @param mixed $terms
     * @return static
     */
    public function terms($terms)
    {
        return $this->addProperty('terms', $terms, true);
    }

    /**
     * @param mixed $order
     * @return static
     */
    public function order($order)
    {
        return $this->addProperty('order', $order);
    }

    /**
     * @param int $size
     * @return static
     */
    public function size($size)
    {
        return $this->addProperty('size', $size);
    }

    /**
     * @param int $shardSize
     * @return static
     */
    public function shardSize($shardSize)
    {
        return $this->addProperty('shard_size', $shardSize);
    }

    /**
     * @param int $minDocCount
     * @return static
     */
    public function minDocCount($minDocCount)
    {
        return $this->addProperty('min_doc_count', $minDocCount);
    }

    /**
     * @param int $shardMinDocCount
     * @return static
     */
    public function shardMinDocCount($shardMinDocCount)
    {
        return $this->addProperty('shard_min_doc_count', $shardMinDocCount);
    }

    /**
     * @param string $collectMode
     * @return static
     */
    public function collectMode($collectMode)
    {
        return $this->addProperty('collect_mode', $collectMode);
    }
}
