<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

class IpPrefix extends Node
{
    protected $_key = 'ip_prefix';

    /**
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * Length of the network prefix.
     *
     * @param int $length
     * @return static
     */
    public function prefixLength($length)
    {
        return $this->addProperty('prefix_length', $length);
    }

    /**
     * @param int $length
     * @return static
     */
    public function minPrefixLength($length)
    {
        return $this->addProperty('min_prefix_length', $length);
    }
}
