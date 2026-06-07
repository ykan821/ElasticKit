<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

class RareTerms extends Node
{
    protected $_key = 'rare_terms';

    /**
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * @param int $maxDocCount
     * @return static
     */
    public function maxDocCount($maxDocCount)
    {
        return $this->addProperty('max_doc_count', $maxDocCount);
    }

    /**
     * @param mixed $precision
     * @return static
     */
    public function precision($precision)
    {
        return $this->addProperty('precision', $precision);
    }

    /**
     * @param mixed $include
     * @return static
     */
    public function include($include)
    {
        return $this->addProperty('include', $include);
    }

    /**
     * @param mixed $exclude
     * @return static
     */
    public function exclude($exclude)
    {
        return $this->addProperty('exclude', $exclude);
    }

    /**
     * @param mixed $missing
     * @return static
     */
    public function missing($missing)
    {
        return $this->addProperty('missing', $missing);
    }

    /**
     * @param int $shardSize
     * @return static
     */
    public function shardSize($shardSize)
    {
        return $this->addProperty('shard_size', $shardSize);
    }
}
