<?php

namespace ElasticKit\DSL\Queries\Specialized;

use ElasticKit\DSL\Query;
use ElasticKit\DSL\Node;

/**
 * Promotes selected documents to the top of the search results.
 */
class Pinned extends Node
{
    protected $_key = 'pinned';

    /**
     * List of document IDs to pin to the top of the results.
     *
     * @param array<int, string> $ids
     * @return static
     */
    public function ids($ids)
    {
        return $this->addProperty('ids', $ids);
    }

    /**
     * The organic query used to rank non-pinned documents.
     *
     * @param mixed $organic
     * @return static
     */
    public function organic($organic)
    {
        return $this->addProperty('organic', Query::create($organic));
    }

    /**
     * A document to pin instead of using an ID.
     *
     * @param mixed $doc
     * @return static
     */
    public function doc($doc)
    {
        return $this->addProperty('doc', $doc);
    }
}
