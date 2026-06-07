<?php

namespace ElasticKit\DSL\Queries\Specialized;

use ElasticKit\DSL\Node;

/**
 * Matches documents against registered percolator queries.
 */
class Percolate extends Node
{
    protected $_key = 'percolate';

    /**
     * The source document to percolate against registered queries.
     *
     * @param mixed $document
     * @return static
     */
    public function document($document)
    {
        return $this->addProperty('document', $document);
    }
}
