<?php

namespace ElasticKit\DSL\Queries\Specialized;

use ElasticKit\DSL\Node;

/**
 * Accepts a query in base64 encoded format.
 */
class Wrapper extends Node
{
    protected $_key = 'wrapper';

    /**
     * Create an instance from various input formats.
     *
     * - String: creates instance with the base64 query set (shorthand).
     * - Other: delegates to parent::create().
     *
     * @param mixed $field
     * @param mixed $value
     * @return static
     */
    public static function create($field = null, $value = null)
    {
        if ($value === null && is_string($field)) {
            return (new static())->query($field);
        }
        return parent::create($field, $value);
    }

    /**
     * A query in base64 encoded format.
     *
     * @param string $query
     * @return static
     */
    public function query($query)
    {
        return $this->addProperty('query', $query);
    }
}
