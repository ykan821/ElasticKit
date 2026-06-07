<?php

namespace ElasticKit\DSL\Queries\FullText\Intervals;

use ElasticKit\DSL\Node;
use ElasticKit\DSL\Queries\FullText\Intervals;

/**
 * The all_of rule returns matches that span a combination of other rules.
 */
class AllOf extends Node
{
    protected $_key = 'all_of';

    /**
     * An array of rules to combine. All rules
     * must produce a match in a document for the overall source to match.
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
     * Maximum number of positions between the matching
     * terms. Intervals produced by the rules further apart than this are not
     * considered matches. Defaults to -1 (no restriction).
     *
     * @param int $maxGaps
     * @return static
     */
    public function maxGaps($maxGaps)
    {
        return $this->addProperty('max_gaps', $maxGaps);
    }

    /**
     * If true, intervals produced by the rules should
     * appear in the order in which they are specified. Defaults to false.
     *
     * @param bool $ordered
     * @return static
     */
    public function ordered($ordered)
    {
        return $this->addProperty('ordered', $ordered);
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
        return $this->addProperty('filter', $filter);
    }
}
