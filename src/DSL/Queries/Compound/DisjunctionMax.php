<?php

namespace ElasticKit\DSL\Queries\Compound;

use ElasticKit\DSL\Shared\ClausesSupport;
use ElasticKit\DSL\Node;
use ElasticKit\DSL\Query;

/**
 * Returns documents matching one or more wrapped queries, called query clauses or clauses.
 */
class DisjunctionMax extends Node
{
    use ClausesSupport;

    protected $_key = 'dis_max';

    /**
     * Contains one or more query clauses. Returned documents must match one or more of these queries. If a document matches multiple queries, Elasticsearch uses the highest relevance score.
     *
     * @param mixed $queries
     * @return static
     */
    public function queries($queries)
    {
        return $this->addProperty('queries', Query::create($queries)->multi(true));
    }

    /**
     * Append a query clause. Supports multiple calls to incrementally build.
     *
     * @param mixed $query
     * @return static
     */
    public function addQuery($query)
    {
        return $this->pushClause('queries', $query);
    }

    /**
     * Floating point number between 0 and 1.0 used to increase the relevance scores of documents matching multiple query clauses. Defaults to 0.0.
     *
     * @param float $tieBreaker
     * @return static
     */
    public function tieBreaker($tieBreaker)
    {
        return $this->addProperty('tie_breaker', $tieBreaker);
    }
}
