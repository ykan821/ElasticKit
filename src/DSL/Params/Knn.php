<?php

namespace ElasticKit\DSL\Params;

use ElasticKit\DSL\Node;
use ElasticKit\DSL\Query;
use stdClass;

/**
 * Performs a k-nearest neighbor (kNN) search on a dense_vector field.
 *
 * @phpstan-consistent-constructor
 */
class Knn extends Node
{
    protected $_key = 'knn';

    /**
     * The vector field to search against.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * The query vector to search for.
     *
     * @param array<int|float> $vector
     * @return static
     */
    public function queryVector($vector)
    {
        return $this->addProperty('query_vector', $vector);
    }

    /**
     * Number of nearest neighbors to return as top hits.
     * Defaults to the search request's size.
     *
     * @param int $k
     * @return static
     */
    public function k($k)
    {
        return $this->addProperty('k', $k);
    }

    /**
     * Number of candidates to evaluate per shard.
     * Defaults to max(k * 4, 50).
     *
     * @param int $num
     * @return static
     */
    public function numCandidates($num)
    {
        return $this->addProperty('num_candidates', $num);
    }

    /**
     * Minimum similarity threshold for a vector to be
     * considered a match.
     *
     * @param float $similarity
     * @return static
     */
    public function similarity($similarity)
    {
        return $this->addProperty('similarity', $similarity);
    }

    /**
     * Boost value for the kNN score.
     *
     * @param float $boost
     * @return static
     */
    public function boost($boost)
    {
        return $this->addProperty('boost', $boost);
    }

    /**
     * (Optional) Pre-filter applied during kNN search. Accepts a closure,
     * array, or Query object.
     *
     * @param mixed $filter
     * @return static
     */
    public function filter($filter)
    {
        return $this->addProperty('filter', Query::create($filter));
    }

    /**
     * (Optional) Inner hits configuration for nested kNN search.
     *
     * @param mixed $innerHits
     * @return static
     */
    public function innerHits($innerHits)
    {
        return $this->addProperty('inner_hits', $innerHits);
    }

    /**
     * (Optional) Rescore vector configuration for quantized vector rescoring.
     *
     * @param array<string, mixed> $rescoreVector
     * @return static
     */
    public function rescoreVector($rescoreVector)
    {
        return $this->addProperty('rescore_vector', $rescoreVector);
    }

    public function toArray()
    {
        $result = parent::toArray();
        if (isset($result['filter']) && $result['filter'] instanceof Query) {
            $result['filter'] = $result['filter']->toArray()['query'] ?? new stdClass();
        }
        return $result;
    }
}
