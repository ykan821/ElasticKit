<?php

namespace ElasticKit\DSL\Queries\Specialized;

use ElasticKit\DSL\Node;

/**
 * Scores documents by distance from an origin point or date.
 */
class DistanceFeature extends Node
{
    protected $_key = 'distance_feature';

    /**
     * Location or date to use as the origin from which to calculate distance.
     *
     * @param mixed $origin
     * @return static
     */
    public function origin($origin)
    {
        return $this->addProperty('origin', $origin);
    }

    /**
     * Distance from the origin at which relevance scores receive half of the boost value.
     *
     * @param mixed $pivot
     * @return static
     */
    public function pivot($pivot)
    {
        return $this->addProperty('pivot', $pivot);
    }
}
