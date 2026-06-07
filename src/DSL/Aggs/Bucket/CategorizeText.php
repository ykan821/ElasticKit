<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that categorizes text fields into buckets based on similarity.
 */
class CategorizeText extends Node
{
    protected $_key = 'categorize_text';

    /**
     * Analyzer used for categorization.
     *
     * @param mixed $categorizationAnalyzer
     * @return static
     */
    public function categorizationAnalyzer($categorizationAnalyzer)
    {
        return $this->addProperty('categorization_analyzer', $categorizationAnalyzer);
    }

    /**
     * Filters applied to each token before categorization.
     *
     * @param array<string, mixed> $categorizationFilters
     * @return static
     */
    public function categorizationFilters($categorizationFilters)
    {
        return $this->addProperty('categorization_filters', $categorizationFilters);
    }

    /**
     * Maximum number of matched tokens to consider.
     *
     * @param int $maxMatchedTokens
     * @return static
     */
    public function maxMatchedTokens($maxMatchedTokens)
    {
        return $this->addProperty('max_matched_tokens', $maxMatchedTokens);
    }

    /**
     * Maximum number of unique tokens to consider.
     *
     * @param int $maxUniqueTokens
     * @return static
     */
    public function maxUniqueTokens($maxUniqueTokens)
    {
        return $this->addProperty('max_unique_tokens', $maxUniqueTokens);
    }

    /**
     * Minimum document count per bucket.
     *
     * @param int $minDocCount
     * @return static
     */
    public function minDocCount($minDocCount)
    {
        return $this->addProperty('min_doc_count', $minDocCount);
    }

    /**
     * Minimum document count per shard.
     *
     * @param int $shardMinDocCount
     * @return static
     */
    public function shardMinDocCount($shardMinDocCount)
    {
        return $this->addProperty('shard_min_doc_count', $shardMinDocCount);
    }

    /**
     * Number of categories to return from each shard.
     *
     * @param int $shardSize
     * @return static
     */
    public function shardSize($shardSize)
    {
        return $this->addProperty('shard_size', $shardSize);
    }

    /**
     * Similarity threshold for grouping categories.
     *
     * @param float $similarityThreshold
     * @return static
     */
    public function similarityThreshold($similarityThreshold)
    {
        return $this->addProperty('similarity_threshold', $similarityThreshold);
    }

    /**
     * Maximum number of categories to return.
     *
     * @param int $size
     * @return static
     */
    public function size($size)
    {
        return $this->addProperty('size', $size);
    }
}
