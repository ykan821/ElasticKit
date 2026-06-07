<?php

namespace ElasticKit\DSL\Queries\TermLevel;

use ElasticKit\DSL\Node;

class Terms extends Node
{
    protected $_key = 'terms';

    protected $_isPropertyField = true;

    /**
     * Field you wish to search.
     *
     * The value of this parameter is an array of terms you wish to find in the provided field. To return a document, one or more terms must exactly match a field value, including whitespace and capitalization.
     *
     * By default, Elasticsearch limits the terms query to a maximum of 65,536 terms. You can change this limit using the index.max_terms_count setting.
     *
     * @param string $field
     * @param array<int, string> $values
     * @return static
     */
    public function values($field, $values)
    {
        return $this->addProperty($field, $values);
    }
}
