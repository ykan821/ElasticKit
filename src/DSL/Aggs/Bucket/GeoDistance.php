<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that groups documents into buckets based on distance from a geo point.
 */
class GeoDistance extends Node
{
    protected $_key = 'geo_distance';

    /**
     * The geo point field to aggregate on.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * The central geo point from which distances are measured.
     *
     * @param mixed $origin
     * @return static
     */
    public function origin($origin)
    {
        return $this->addProperty('origin', $origin);
    }

    /**
     * Distance unit (e.g. km, mi, m).
     *
     * @param string $unit
     * @return static
     */
    public function unit($unit)
    {
        return $this->addProperty('unit', $unit);
    }

    /**
     * How to compute the distance (arc or plane).
     *
     * @param string $distanceType
     * @return static
     */
    public function distanceType($distanceType)
    {
        return $this->addProperty('distance_type', $distanceType);
    }

    /**
     * Array of distance range definitions for bucketing.
     *
     * @param array<string, mixed> $ranges
     * @return static
     */
    public function ranges($ranges)
    {
        return $this->addProperty('ranges', $ranges);
    }

    /**
     * Whether to return range buckets as a hash keyed by range key.
     *
     * @param bool $keyed
     * @return static
     */
    public function keyed($keyed)
    {
        return $this->addProperty('keyed', $keyed);
    }
}
