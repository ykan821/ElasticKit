<?php

namespace ElasticKit\DSL\Queries\FullText;

use ElasticKit\DSL\Node;

/**
 * The match_bool_prefix query analyzes its input and constructs a bool query
 * from the terms. Each term except the last is used in a term query. The last
 * term is used in a prefix query.
 */
class MatchBoolPrefix extends Node
{
    protected $_key = 'match_bool_prefix';

    protected $_isPropertyField = true;

    protected $_valueKey = 'query';

    /**
     * Text you wish to find in the provided field.
     * The match_bool_prefix query analyzes any provided text into tokens
     * before performing a search.
     *
     * @param string $query
     * @return static
     */
    public function query($query)
    {
        return $this->addProperty('query', $query);
    }

    /**
     * Maximum number of terms to which the last provided
     * term of the query value will expand. Defaults to 50.
     *
     * @param int $maxExpansions
     * @return static
     */
    public function maxExpansions($maxExpansions)
    {
        return $this->addProperty('max_expansions', $maxExpansions);
    }

    /**
     * If true, format-based errors, such as providing a
     * text query value for a numeric field, are ignored. Defaults to false.
     *
     * @param bool $lenient
     * @return static
     */
    public function lenient($lenient)
    {
        return $this->addProperty('lenient', $lenient);
    }

    /**
     * Analyzer used to convert text in the query value
     * into tokens. Defaults to the index-time analyzer mapped for the field.
     *
     * @param string $analyzer
     * @return static
     */
    public function analyzer($analyzer)
    {
        return $this->addProperty('analyzer', $analyzer);
    }

    /**
     * Minimum number of clauses that must match for a
     * document to be returned.
     *
     * @param string $minimumShouldMatch
     * @return static
     */
    public function minimumShouldMatch($minimumShouldMatch)
    {
        return $this->addProperty('minimum_should_match', $minimumShouldMatch);
    }

    /**
     * Maximum edit distance allowed for fuzzy matching.
     *
     * @param string $fuzziness
     * @return static
     */
    public function fuzziness($fuzziness)
    {
        return $this->addProperty('fuzziness', $fuzziness);
    }

    /**
     * Number of beginning characters left unchanged for
     * fuzzy matching. Defaults to 0.
     *
     * @param int $prefixLength
     * @return static
     */
    public function prefixLength($prefixLength)
    {
        return $this->addProperty('prefix_length', $prefixLength);
    }

    /**
     * If true, edits for fuzzy matching include
     * transpositions of two adjacent characters (ab -> ba). Defaults to true.
     *
     * @param bool $fuzzyTranspositions
     * @return static
     */
    public function fuzzyTranspositions($fuzzyTranspositions)
    {
        return $this->addProperty('fuzzy_transpositions', $fuzzyTranspositions);
    }

    /**
     * Method used to rewrite the query for fuzzy matching.
     *
     * @param string $fuzzyRewrite
     * @return static
     */
    public function fuzzyRewrite($fuzzyRewrite)
    {
        return $this->addProperty('fuzzy_rewrite', $fuzzyRewrite);
    }

    /**
     * Boolean logic used to interpret text in the query
     * value. Valid values are: OR (Default), AND.
     *
     * @param string $operator
     * @return static
     */
    public function operator($operator)
    {
        return $this->addProperty('operator', $operator);
    }
}
