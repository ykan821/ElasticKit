<?php

namespace ElasticKit\DSL\Aggs\Bucket;

use ElasticKit\DSL\Node;

class TimeSeries extends Node
{
    protected $_key = 'time_series';

    /**
     * @param string $field
     * @return static
     */
    public function field($field)
    {
        return $this->addProperty('field', $field);
    }

    /**
     * @param string $calendarInterval
     * @return static
     */
    public function calendarInterval($calendarInterval)
    {
        return $this->addProperty('calendar_interval', $calendarInterval);
    }

    /**
     * @param string $fixedInterval
     * @return static
     */
    public function fixedInterval($fixedInterval)
    {
        return $this->addProperty('fixed_interval', $fixedInterval);
    }

    /**
     * @param mixed $missing
     * @return static
     */
    public function missing($missing)
    {
        return $this->addProperty('missing', $missing);
    }
}
