<?php

namespace ElasticKit\DSL\Queries\TermLevel;

use ElasticKit\DSL\Node;

class Regexp extends Node
{
    protected $_key = 'regexp';

    protected $_isPropertyField = true;

    /**
     * Regular expression for terms you wish to find in the provided <field>. For a list of supported operators, see Regular expression syntax.
     *
     * By default, regular expressions are limited to 1,000 characters. You can change this limit using the index.max_regex_length setting.
     *
     * The performance of the regexp query can vary based on the regular expression provided. To improve performance, avoid using wildcard patterns, such as .* or .*?+, without a prefix or suffix.
     *
     * @param string $value
     * @return static
     */
    public function value($value)
    {
        return $this->addProperty('value', $value);
    }

    /**
     * Enables optional operators for the regular expression. For valid values and more information, see Regular expression syntax.
     *
     * @param string $flags
     * @return static
     */
    public function flags($flags)
    {
        return $this->addProperty('flags', $flags);
    }

    /**
     * Allows case insensitive matching of the regular expression value with the indexed field values when set to true. Default is false which means the case sensitivity of matching depends on the underlying field’s mapping.
     *
     * @param bool $caseInsensitive
     * @return static
     */
    public function caseInsensitive($caseInsensitive)
    {
        return $this->addProperty('case_insensitive', $caseInsensitive);
    }

    /**
     * Maximum number of automaton states required for the query. Default is 10000.
     *
     * Elasticsearch uses Apache Lucene internally to parse regular expressions. Lucene converts each regular expression to a finite automaton containing a number of determinized states.
     *
     * You can use this parameter to prevent that conversion from unintentionally consuming too many resources. You may need to increase this limit to run complex regular expressions.
     *
     * @param int $maxDeterminizedStates
     * @return static
     */
    public function maxDeterminizedStates($maxDeterminizedStates)
    {
        return $this->addProperty('max_determinized_states', $maxDeterminizedStates);
    }

    /**
     * Method used to rewrite the query. For valid values and more information, see the rewrite parameter.
     *
     * @param string $rewrite
     * @return static
     */
    public function rewrite($rewrite)
    {
        return $this->addProperty('rewrite', $rewrite);
    }
}
