<?php

namespace ElasticKit\DSL\Queries\Span;

use ElasticKit\DSL\Query;
use ElasticKit\DSL\Node;

/**
 * Allows using span queries across multiple fields by masking one field as another.
 */
class SpanFieldMasking extends Node
{
    protected $_key = 'span_field_masking';

    /**
     * The inner span query to execute.
     *
     * @param mixed $query
     * @return static
     */
    public function query($query)
    {
        return $this->addProperty('query', Query::create($query));
    }

    /**
     * The masked field to use for the span query.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }
}
