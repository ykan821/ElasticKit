<?php

namespace ElasticKit\DSL\Queries\Span;

use ElasticKit\DSL\Shared\ClausesSupport;
use ElasticKit\DSL\Node;
use ElasticKit\DSL\Query;

/**
 * Matches the union of multiple span queries, combining their results.
 */
class SpanOr extends Node
{
    use ClausesSupport;

    protected $_key = 'span_or';

    /**
     * The list of span query clauses to combine.
     *
     * @param mixed $clauses
     * @return static
     */
    public function clauses($clauses)
    {
        return $this->addProperty('clauses', Query::create($clauses)->multi(true));
    }

    /**
     * Append a span query clause. Supports multiple calls to incrementally build.
     *
     * @param mixed $clause
     * @return static
     */
    public function addClause($clause)
    {
        return $this->pushClause('clauses', $clause);
    }
}
