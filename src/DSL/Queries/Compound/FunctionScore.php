<?php

namespace ElasticKit\DSL\Queries\Compound;

use ElasticKit\DSL\Query;
use ElasticKit\DSL\Node;
use ElasticKit\DSL\Queries\Compound\Functions\Function_;

/**
 * Modifies the score of documents retrieved by a query using one or more score functions.
 */
class FunctionScore extends Node
{
    protected $_key = 'function_score';

    /**
     * (Optional) Controls how the computed scores from multiple functions are combined.
     * Options: multiply (default), sum, avg, first, max, min.
     *
     * @param string $scoreMode
     * @return static
     */
    public function scoreMode($scoreMode)
    {
        return $this->addProperty('score_mode', $scoreMode);
    }

    /**
     * (Optional) Defines how the newly computed function score is combined with the query score.
     * Options: multiply (default), replace, sum, avg, max, min.
     *
     * @param string $boostMode
     * @return static
     */
    public function boostMode($boostMode)
    {
        return $this->addProperty('boost_mode', $boostMode);
    }

    /**
     * (Optional) Excludes documents that do not meet the specified score threshold.
     *
     * @param float $minScore
     * @return static
     */
    public function minScore($minScore)
    {
        return $this->addProperty('min_score', $minScore);
    }

    /**
     * (Optional) Restricts the new score to not exceed the specified limit. Defaults to FLT_MAX.
     *
     * @param float $maxBoost
     * @return static
     */
    public function maxBoost($maxBoost)
    {
        return $this->addProperty('max_boost', $maxBoost);
    }

    /**
     * (Required) The query to be scored.
     *
     * @param mixed $query
     * @return static
     */
    public function query($query)
    {
        return $this->addProperty('query', Query::create($query));
    }

    /**
     * (Optional) Array of score functions to apply.
     *
     * @param array<int, mixed> $functions
     * @return static
     */
    public function functions($functions)
    {
        return $this->addProperty('functions', $functions);
    }

    /**
     * (Optional) Appends a score function to the functions array.
     *
     * @param mixed $function
     * @return static
     */
    public function addFunction($function)
    {
        return $this->addProperty('functions', Function_::create($function), true);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $properties = $this->_properties;

        if (!empty($properties['functions'])) {
            foreach ($properties['functions'] as $key => $function) {
                if ($function instanceof Function_) {
                    $properties['functions'][$key] = $function->toArray();
                } elseif ($function instanceof Node) {
                    $properties['functions'][$key] = [$function->key() => $function->toArray()];
                } elseif (!empty($function['filter']) && $function['filter'] instanceof Node) {
                    $function['filter'] = $function['filter']->toArray()['query'];
                    $properties['functions'][$key] = $function;
                }
            }
        }

        $properties = $this->resolveProperties($properties);

        if ($this->_isPropertyField) {
            return [$this->_field => $properties];
        }
        return $properties;
    }
}
