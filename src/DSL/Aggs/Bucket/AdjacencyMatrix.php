<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Query;
use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that creates buckets based on the combinations of filter matches.
 */
class AdjacencyMatrix extends Node
{
    protected $_key = 'adjacency_matrix';

    /** @var bool */
    protected $_isPropertyField;

    /** @var array<string, Query> */
    protected $_filters;

    /**
     * Filters used to create buckets.
     *
     * @param string $key
     * @param mixed $query
     * @return static
     */
    public function filters($key, $query)
    {
        $this->_filters[$key] = Query::create($query);
        return $this->addProperty('filters', $this->_filters);
    }

    /**
     * Separator used to concatenate filter names. Defaults to &.
     *
     * @param string $separator
     * @return static
     */
    public function separator($separator)
    {
        return $this->addProperty('separator', $separator);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $properties = $this->_properties;

        if (isset($properties['filters'])) {
            foreach ($properties['filters'] as $key => $filter) {
                if ($filter instanceof Query) {
                    $properties['filters'][$key] = $filter->toArray()['query'];
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
