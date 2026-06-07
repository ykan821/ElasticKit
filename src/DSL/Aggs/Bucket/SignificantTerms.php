<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that returns interesting or unusual occurrences of terms in a field.
 */
class SignificantTerms extends Node
{
    protected $_key = 'significant_terms';

    /**
     * The field to aggregate on.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * Maximum number of significant terms to return.
     *
     * @param int $size
     * @return static
     */
    public function size($size)
    {
        return $this->addProperty('size', $size);
    }

    /**
     * Number of candidate terms to return from each shard.
     *
     * @param int $shardSize
     * @return static
     */
    public function shardSize($shardSize)
    {
        return $this->addProperty('shard_size', $shardSize);
    }

    /**
     * Minimum document count for a term to be returned.
     *
     * @param int $minDocCount
     * @return static
     */
    public function minDocCount($minDocCount)
    {
        return $this->addProperty('min_doc_count', $minDocCount);
    }

    /**
     * Minimum document count for a term to be considered on each shard.
     *
     * @param int $shardMinDocCount
     * @return static
     */
    public function shardMinDocCount($shardMinDocCount)
    {
        return $this->addProperty('shard_min_doc_count', $shardMinDocCount);
    }

    /**
     * Terms to include in the aggregation.
     *
     * @param mixed $include
     * @return static
     */
    public function include($include)
    {
        return $this->addProperty('include', $include);
    }

    /**
     * Terms to exclude from the aggregation.
     *
     * @param mixed $exclude
     * @return static
     */
    public function exclude($exclude)
    {
        return $this->addProperty('exclude', $exclude);
    }

    /**
     * Query to filter the background document set for significance calculation.
     *
     * @param mixed $backgroundFilter
     * @return static
     */
    public function backgroundFilter($backgroundFilter)
    {
        return $this->addProperty('background_filter', $backgroundFilter);
    }

    /**
     * Execution hint for the aggregation mechanism.
     *
     * @param string $executionHint
     * @return static
     */
    public function executionHint($executionHint)
    {
        return $this->addProperty('execution_hint', $executionHint);
    }
}
