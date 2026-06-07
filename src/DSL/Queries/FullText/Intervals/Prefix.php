<?php

namespace ElasticKit\DSL\Queries\FullText\Intervals;

use ElasticKit\DSL\Node;

/**
 * The prefix rule matches terms that start with a specified set of characters.
 * This prefix can expand to match at most 128 terms. If the prefix matches
 * more than 128 terms, Elasticsearch returns an error.
 */
class Prefix extends Node
{
    protected $_key = 'prefix';

    /**
     * Beginning characters of terms you wish to find in
     * the top-level field.
     *
     * @param string $prefix
     * @return static
     */
    public function prefix($prefix)
    {
        return $this->addProperty('prefix', $prefix);
    }

    /**
     * Analyzer used to normalize the prefix. Defaults to
     * the top-level field's analyzer.
     *
     * @param string $analyzer
     * @return static
     */
    public function analyzer($analyzer)
    {
        return $this->addProperty('analyzer', $analyzer);
    }

    /**
     * If specified, match intervals from this field rather
     * than the top-level field. The prefix is normalized using the search
     * analyzer from this field.
     *
     * @param string $userField
     * @return static
     */
    public function useField($userField)
    {
        return $this->addProperty('use_field', $userField);
    }
}
