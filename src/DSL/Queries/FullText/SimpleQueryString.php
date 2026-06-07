<?php

namespace ElasticKit\DSL\Queries\FullText;

use ElasticKit\DSL\Node;

/**
 * Returns documents based on a provided query string, using a parser with a
 * limited but fault-tolerant syntax. Unlike the query_string query, the
 * simple_query_string query does not return errors for invalid syntax. Instead,
 * it ignores any invalid parts of the query string.
 */
class SimpleQueryString extends Node
{
    protected $_key = 'simple_query_string';

    /**
     * Query string you wish to parse and use for search.
     *
     * @param string $query
     * @return static
     */
    public function query($query)
    {
        return $this->addProperty('query', $query);
    }

    /**
     * Array of fields you wish to search.
     * Supports wildcard expressions and per-field boosting with caret (^)
     * notation.
     *
     * @param array<int, string> $fields
     * @return static
     */
    public function fields($fields)
    {
        return $this->addProperty('fields', $fields);
    }

    /**
     * Default boolean logic used to interpret text in the
     * query string if no operators are specified. Valid values are:
     * OR (Default), AND.
     *
     * @param string $defaultOperator
     * @return static
     */
    public function defaultOperator($defaultOperator)
    {
        return $this->addProperty('default_operator', $defaultOperator);
    }

    /**
     * If true, the query attempts to analyze wildcard terms
     * in the query string. Defaults to false.
     *
     * @param bool $analyzeWildcard
     * @return static
     */
    public function analyzeWildcard($analyzeWildcard)
    {
        return $this->addProperty('analyze_wildcard', $analyzeWildcard);
    }

    /**
     * Analyzer used to convert text in the query string
     * into tokens. Defaults to the index-time analyzer mapped for the
     * default_field.
     *
     * @param string $analyzer
     * @return static
     */
    public function analyzer($analyzer)
    {
        return $this->addProperty('analyzer', $analyzer);
    }

    /**
     * If true, the parser creates a match_phrase query
     * for each multi-position token. Defaults to true.
     *
     * @param bool $autoGenerateSynonymsPhraseQuery
     * @return static
     */
    public function autoGenerateSynonymsPhraseQuery($autoGenerateSynonymsPhraseQuery)
    {
        return $this->addProperty('auto_generate_synonyms_phrase_query', $autoGenerateSynonymsPhraseQuery);
    }

    /**
     * List of enabled operators for the simple query string
     * syntax. Defaults to ALL (all operators).
     *
     * @param string $flags
     * @return static
     */
    public function flags($flags)
    {
        return $this->addProperty('flags', $flags);
    }

    /**
     * Maximum number of terms to which the query expands
     * for fuzzy matching. Defaults to 50.
     *
     * @param int $fuzzyMaxExpansions
     * @return static
     */
    public function fuzzyMaxExpansions($fuzzyMaxExpansions)
    {
        return $this->addProperty('fuzzy_max_expansions', $fuzzyMaxExpansions);
    }

    /**
     * Number of beginning characters left unchanged for
     * fuzzy matching. Defaults to 0.
     *
     * @param int $fuzzyPrefixLength
     * @return static
     */
    public function fuzzyPrefixLength($fuzzyPrefixLength)
    {
        return $this->addProperty('fuzzy_prefix_length', $fuzzyPrefixLength);
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
     * If true, format-based errors, such as providing a
     * text value for a numeric field, are ignored. Defaults to false.
     *
     * @param bool $lenient
     * @return static
     */
    public function lenient($lenient)
    {
        return $this->addProperty('lenient', $lenient);
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
     * Suffix appended to quoted text in the query string.
     * You can use this suffix to use a different analysis method for exact
     * matches.
     *
     * @param string $quoteFieldSuffix
     * @return static
     */
    public function quoteFieldSuffix($quoteFieldSuffix)
    {
        return $this->addProperty('quote_field_suffix', $quoteFieldSuffix);
    }
}
