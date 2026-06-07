<?php

namespace ElasticKit\DSL\Queries\Span;

use ElasticKit\DSL\Query;
use ElasticKit\DSL\Node;

/**
 * Matches a single term as a span query, the simplest span query type.
 */
class SpanTerm extends Node
{
    protected $_key = 'span_term';

    protected $_isPropertyField = true;

    /**
     * The value of the term to match.
     *
     * @param string $term
     * @return static
     */
    public function term($term)
    {
        return $this->addProperty('term', $term);
    }

    /**
     * The value of the term to match (alias for the field value).
     *
     * @param string $value
     * @return static
     */
    public function value($value)
    {
        return $this->addProperty('value', $value);
    }
}
