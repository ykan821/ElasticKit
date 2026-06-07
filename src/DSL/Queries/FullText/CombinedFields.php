<?php

namespace ElasticKit\DSL\Queries\FullText;

use ElasticKit\DSL\Node;

/**
 * The combined_fields query supports searching multiple text fields as if their
 * contents had been indexed into one combined field. The query takes a
 * term-centric view of the input string: first it analyzes the query string
 * into individual terms, then looks for each term in any of the fields.
 */
class CombinedFields extends Node
{
    protected $_key = 'combined_fields';

    /**
     * Text to search for in the provided fields.
     * The combined_fields query analyzes the provided text before performing
     * a search.
     *
     * @param string $query
     * @return static
     */
    public function query($query)
    {
        return $this->addProperty('query', $query);
    }

    /**
     * List of fields to search. Field wildcard
     * patterns are allowed. Only text fields are supported, and they must all
     * have the same search analyzer.
     *
     * @param array<int, string> $fields
     * @return static
     */
    public function fields($fields)
    {
        return $this->addProperty('fields', $fields);
    }

    /**
     * If true, match phrase queries are automatically
     * created for multi-term synonyms. Defaults to true.
     *
     * @param bool $autoGenerateSynonymsPhraseQuery
     * @return static
     */
    public function autoGenerateSynonymsPhraseQuery($autoGenerateSynonymsPhraseQuery)
    {
        return $this->addProperty('auto_generate_synonyms_phrase_query', $autoGenerateSynonymsPhraseQuery);
    }

    /**
     * Boolean logic used to interpret text in the query
     * value. Valid values are: or (Default), and.
     *
     * @param string $operator
     * @return static
     */
    public function operator($operator)
    {
        return $this->addProperty('operator', $operator);
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
     * Indicates whether no documents are returned if the
     * analyzer removes all tokens, such as when using a stop filter.
     * Valid values are: none (Default), all.
     *
     * @param string $zeroTermsQuery
     * @return static
     */
    public function zeroTermsQuery($zeroTermsQuery)
    {
        return $this->addProperty('zero_terms_query', $zeroTermsQuery);
    }
}
