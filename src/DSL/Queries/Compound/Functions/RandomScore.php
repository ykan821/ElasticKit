<?php

namespace ElasticKit\DSL\Queries\Compound\Functions;

use ElasticKit\DSL\Node;

/**
 * Generates uniformly distributed random scores from 0 up to but not including 1.
 */
class RandomScore extends Node
{
    protected $_key = 'random_score';

    /**
     * (Optional) Seed value for reproducible random scores.
     *
     * @param mixed $seed
     * @return static
     */
    public function seed($seed)
    {
        return $this->addProperty('seed', $seed);
    }

    /**
     * (Optional) Field used in combination with the seed to compute reproducible random scores.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->_properties ?: (object)[];
    }
}
