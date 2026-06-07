<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

/**
 * A bucket aggregation that limits any child aggregations to a random sample of documents.
 */
class RandomSampler extends Node
{
    protected $_key = 'random_sampler';

    /**
     * Probability that a document is included in the sample (between 0 and 1).
     *
     * @param float $probability
     * @return static
     */
    public function probability($probability)
    {
        return $this->addProperty('probability', $probability);
    }

    /**
     * Seed for the random number generator to produce repeatable samples.
     *
     * @param int $seed
     * @return static
     */
    public function seed($seed)
    {
        return $this->addProperty('seed', $seed);
    }

    /**
     * Field used to maintain consistent random ordering across shards.
     *
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }
}
