<?php

namespace ElasticKit\DSL\Queries\Specialized;

use ElasticKit\DSL\Node;

/**
 * Finds documents similar to given text, documents, or collections of documents.
 */
class MoreLikeThis extends Node
{
    protected $_key = 'more_like_this';

    /**
     * List of fields to use for similarity comparison.
     *
     * @param array<int, string> $array
     * @return static
     */
    public function fields($array)
    {
        return $this->addProperty('fields', $array);
    }

    /**
     * Text or documents to find similar documents for.
     *
     * @param mixed $string
     * @return static
     */
    public function like($string)
    {
        return $this->addProperty('like', $string);
    }

    /**
     * Minimum term frequency below which terms are ignored. Defaults to 2.
     *
     * @param int $int
     * @return static
     */
    public function minTermFreq($int)
    {
        return $this->addProperty('min_term_freq', $int);
    }

    /**
     * Maximum number of query terms to be selected per result document. Defaults to 25.
     *
     * @param int $int
     * @return static
     */
    public function maxQueryTerms($int)
    {
        return $this->addProperty('max_query_terms', $int);
    }
}
