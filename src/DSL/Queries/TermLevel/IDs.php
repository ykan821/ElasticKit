<?php

namespace ElasticKit\DSL\Queries\TermLevel;

use ElasticKit\DSL\Node;

/**
 * Returns documents based on their IDs.
 */
class IDs extends Node
{
    protected $_key = 'ids';

    /**
     * An array of document IDs.
     *
     * @param array<int, string> $values
     * @return static
     */
    public function values($values)
    {
        return $this->addProperty('values', $values);
    }
}
