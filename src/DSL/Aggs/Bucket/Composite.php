<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that creates composite buckets from multiple sources.
 */
class Composite extends Node
{
    /** @var string */
    protected $_key = 'composite';

    /** @var mixed */
    protected $sources;

    /**
     * List of source definitions used to build composite buckets.
     *
     * @param mixed $sources
     * @return static
     */
    public function sources($sources)
    {
        return $this->addProperty('sources', $sources);
    }

    /**
     * Cursor value to resume pagination after a previous composite response.
     *
     * @param mixed $after
     * @return static
     */
    public function after($after)
    {
        return $this->addProperty('after', $after);
    }

    /**
     * Sort order for composite buckets.
     *
     * @param mixed $order
     * @return static
     */
    public function order($order)
    {
        return $this->addProperty('order', $order);
    }

    /**
     * Maximum number of composite buckets to return.
     *
     * @param int $size
     * @return static
     */
    public function size($size)
    {
        return $this->addProperty('size', $size);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.IfStatementAssignment)
     */
    public function toArray()
    {
        $properties = $this->_properties;

        if (isset($properties['sources'])) {
            foreach ($properties['sources'] as $key => $source) {
                if (($item = current($source)) instanceof Node) {
                    $properties['sources'][$key] = $item->toArray();
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
