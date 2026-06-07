<?php

namespace ElasticKit\DSL\Queries\FullText\Intervals;

use ElasticKit\DSL\Node;
use ElasticKit\DSL\Queries\FullText\Intervals;

/**
 * The any_of rule returns intervals produced by any of its sub-rules.
 */
class AnyOf extends Node
{
    protected $_key = 'any_of';

    /**
     * An array of rules to match.
     *
     * @param mixed $intervals
     * @return static
     */
    public function intervals($intervals)
    {
        $intervals = Intervals::create($intervals)
            ->isPropertyField(false)
            ->multi(true);
        return $this->addProperty('intervals', $intervals);
    }

    /**
     * Append an interval rule. Supports multiple calls to incrementally build.
     *
     * @param mixed $interval
     * @return static
     */
    public function addInterval($interval)
    {
        if (!isset($this->_properties['intervals'])) {
            $this->_properties['intervals'] = (new Intervals())->isPropertyField(false)->multi(true);
        }
        $target = $this->_properties['intervals'];
        if ($interval instanceof \Closure) {
            $interval($target);
        } elseif ($interval instanceof Node) {
            $target->addQuery($interval);
        }
        return $this;
    }

    /**
     * Rule used to filter returned
     * intervals.
     *
     * @param mixed $filter
     * @return static
     */
    public function filter($filter)
    {
        return $this->addProperty('filter', Filter::create($filter));
    }
}
