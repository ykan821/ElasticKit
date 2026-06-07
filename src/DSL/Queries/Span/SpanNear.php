<?php

namespace ElasticKit\DSL\Queries\Span;

use ElasticKit\DSL\Shared\ClausesSupport;
use ElasticKit\DSL\Node;
use ElasticKit\DSL\Query;

/**
 * Matches spans that are near each other, with configurable slop and ordering.
 */
class SpanNear extends Node
{
    use ClausesSupport;

    protected $_key = 'span_near';

    /**
     * The list of span query clauses that must appear near each other.
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

    /**
     * The maximum number of positions allowed between matching spans.
     *
     * @param int $slop
     * @return static
     */
    public function slop($slop)
    {
        return $this->addProperty('slop', $slop);
    }

    /**
     * Whether the span clauses must appear in their specified order.
     *
     * @param bool $inOrder
     * @return static
     */
    public function inOrder($inOrder)
    {
        return $this->addProperty('in_order', $inOrder);
    }
}
