<?php

namespace ElasticKit\DSL\Queries\Specialized;

use ElasticKit\DSL\Node;

/**
 * Scores documents based on the value of a rank feature field.
 */
class RankFeature extends Node
{
    protected $_key = 'rank_feature';

    /**
     * Saturation function to compute the score. Uses point: 2 by default.
     *
     * @param mixed $saturation
     * @return static
     */
    public function saturation($saturation)
    {
        return $this->addProperty('saturation', $saturation);
    }

    /**
     * Logarithmic function to compute the score. Supports a scaling_factor parameter.
     *
     * @param mixed $log
     * @return static
     */
    public function log($log)
    {
        return $this->addProperty('log', $log);
    }

    /**
     * Sigmoid function to compute the score. Requires exponent and pivot parameters.
     *
     * @param mixed $sigmoid
     * @return static
     */
    public function sigmoid($sigmoid)
    {
        return $this->addProperty('sigmoid', $sigmoid);
    }

    /**
     * Linear function to compute the score, producing a linear relation between the feature value and the score.
     *
     * @param mixed $linear
     * @return static
     */
    public function linear($linear)
    {
        return $this->addProperty('linear', $linear);
    }
}
