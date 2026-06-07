<?php

namespace ElasticKit\DSL\Queries\Compound;

use ElasticKit\DSL\Shared\ClausesSupport;
use ElasticKit\DSL\Node;
use ElasticKit\DSL\Query;

/**
 * A query that matches documents matching boolean combinations of other queries. The bool query maps to Lucene BooleanQuery. It is built using one or more boolean clauses, each clause with a typed occurrence. The occurrence types are:
 */
class Boolean extends Node
{
    use ClausesSupport;

    protected $_key = 'bool';

    /**
     * The clause (query) must appear in matching documents and will contribute to the score.
     *
     * @param mixed $must
     * @return static
     */
    public function must($must)
    {
        return $this->addProperty('must', Query::create($must)->multi(true));
    }

    /**
     * Append a must clause. Supports multiple calls to incrementally build the bool query.
     * Clauses are held in a temporary buffer and merged into the must property during serialization.
     *
     * @param mixed $must
     * @return static
     */
    public function addMust($must)
    {
        return $this->pushClause('must', $must);
    }

    /**
     * The clause (query) should appear in the matching document.
     *
     * @param mixed $should
     * @return static
     */
    public function should($should)
    {
        return $this->addProperty('should', Query::create($should)->multi(true));
    }

    /**
     * Append a should clause. Supports multiple calls to incrementally build the bool query.
     *
     * @param mixed $should
     * @return static
     */
    public function addShould($should)
    {
        return $this->pushClause('should', $should);
    }

    /**
     * The clause (query) must appear in matching documents. However unlike must the score of the query will be ignored. Filter clauses are executed in filter context, meaning that scoring is ignored and clauses are considered for caching.
     *
     * @param mixed $filter
     * @return static
     */
    public function filter($filter)
    {
        return $this->addProperty('filter', Query::create($filter)->multi(true));
    }

    /**
     * Append a filter clause. Supports multiple calls to incrementally build the bool query.
     *
     * @param mixed $filter
     * @return static
     */
    public function addFilter($filter)
    {
        return $this->pushClause('filter', $filter);
    }

    /**
     * The clause (query) must not appear in the matching documents. Clauses are executed in filter context meaning that scoring is ignored and clauses are considered for caching. Because scoring is ignored, a score of 0 for all documents is returned.
     *
     * @param mixed $mustNot
     * @return static
     */
    public function mustNot($mustNot)
    {
        return $this->addProperty('must_not', Query::create($mustNot)->multi(true));
    }

    /**
     * Append a must_not clause. Supports multiple calls to incrementally build the bool query.
     *
     * @param mixed $mustNot
     * @return static
     */
    public function addMustNot($mustNot)
    {
        return $this->pushClause('must_not', $mustNot);
    }

    /**
     * You can use the minimum_should_match parameter to specify the number or percentage of should clauses returned documents must match.
     *
     * If the bool query includes at least one should clause and no must or filter clauses, the default value is 1. Otherwise, the default value is 0.
     *
     * For other valid values, see the minimum_should_match parameter.
     *
     * @param int $minimumShouldMatch
     * @return static
     */
    public function minimumShouldMatch($minimumShouldMatch)
    {
        return $this->addProperty('minimum_should_match', $minimumShouldMatch);
    }
}
