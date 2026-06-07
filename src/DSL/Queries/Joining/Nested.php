<?php

namespace ElasticKit\DSL\Queries\Joining;

use ElasticKit\DSL\Query;
use ElasticKit\DSL\Node;

/**
 * Wraps another query to search nested field objects as if they were indexed as separate documents.
 *
 * If an object matches the search, the nested query returns the root parent document.
 */
class Nested extends Node
{
    protected $_key = 'nested';

    /**
     * Create an instance from various input formats.
     *
     * - String: creates instance with the path set (shorthand).
     * - Other: delegates to parent::create().
     *
     * @param mixed $field
     * @param mixed $value
     * @return static
     */
    public static function create($field = null, $value = null)
    {
        if ($value === null && is_string($field)) {
            return (new static())->path($field);
        }
        return parent::create($field, $value);
    }

    /**
     * Path to the nested object you wish to search.
     *
     * @param string $path
     * @return static
     */
    public function path($path)
    {
        return $this->addProperty('path', $path);
    }

    /**
     * Query you wish to run on nested objects in the path.
     * If an object matches the search, the nested query returns the root parent document.
     *
     * @param mixed $query
     * @return static
     */
    public function query($query)
    {
        return $this->addProperty('query', Query::create($query));
    }

    /**
     * Indicates how scores for matching child objects affect the root parent
     * document's relevance score. Valid values: avg (default), max, min, none, sum.
     *
     * @param string $scoreMode
     * @return static
     */
    public function scoreMode($scoreMode)
    {
        return $this->addProperty('score_mode', $scoreMode);
    }

    /**
     * Indicates whether to ignore an unmapped path and not return any
     * documents instead of an error. Defaults to false.
     *
     * @param bool $ignoreUnmapped
     * @return static
     */
    public function ignoreUnmapped($ignoreUnmapped)
    {
        return $this->addProperty('ignore_unmapped', $ignoreUnmapped);
    }
}
