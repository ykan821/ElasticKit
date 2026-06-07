<?php

namespace ElasticKit\DSL\Queries\Geo;

use ElasticKit\DSL\Node;

/**
 * Matches geo_point and geo_shape values that intersect a bounding box.
 *
 * To define the box, provide geopoint values for two opposite corners.
 */
class GeoBoundingBox extends Node
{
    protected $_key = 'geo_bounding_box';

    protected $_isPropertyField = true;

    /**
     * Top-left corner of the bounding box.
     *
     * @param mixed $topLeft
     * @return static
     */
    public function topLeft($topLeft)
    {
        return $this->addProperty('top_left', $topLeft);
    }

    /**
     * Bottom-right corner of the bounding box.
     *
     * @param mixed $bottomRight
     * @return static
     */
    public function bottomRight($bottomRight)
    {
        return $this->addProperty('bottom_right', $bottomRight);
    }

    /**
     * Top latitude of the bounding box. Can be used instead of topLeft/bottomRight pairs to set values separately.
     *
     * @param float $top
     * @return static
     */
    public function top($top)
    {
        return $this->addProperty('top', $top);
    }

    /**
     * Left longitude of the bounding box. Can be used instead of topLeft/bottomRight pairs to set values separately.
     *
     * @param float $left
     * @return static
     */
    public function left($left)
    {
        return $this->addProperty('left', $left);
    }

    /**
     * Bottom latitude of the bounding box. Can be used instead of topLeft/bottomRight pairs to set values separately.
     *
     * @param float $bottom
     * @return static
     */
    public function bottom($bottom)
    {
        return $this->addProperty('bottom', $bottom);
    }

    /**
     * Right longitude of the bounding box. Can be used instead of topLeft/bottomRight pairs to set values separately.
     *
     * @param float $right
     * @return static
     */
    public function right($right)
    {
        return $this->addProperty('right', $right);
    }

    /**
     * Bounding box defined as Well-Known Text (WKT) BBOX format.
     *
     * @param string $wkt
     * @return static
     */
    public function wkt($wkt)
    {
        return $this->addProperty('wkt', $wkt);
    }

    /**
     * Top-right corner of the bounding box. Can be used with bottomLeft instead of topLeft/bottomRight.
     *
     * @param mixed $topRight
     * @return static
     */
    public function topRight($topRight)
    {
        return $this->addProperty('top_right', $topRight);
    }

    /**
     * Bottom-left corner of the bounding box. Can be used with topRight instead of topLeft/bottomRight.
     *
     * @param mixed $bottomLeft
     * @return static
     */
    public function bottomLeft($bottomLeft)
    {
        return $this->addProperty('bottom_left', $bottomLeft);
    }

    /**
     * Set to IGNORE_MALFORMED to accept geo points with invalid latitude or longitude,
     * set to COERCE to also try to infer correct latitude or longitude. Defaults to STRICT.
     *
     * @param string $validationMethod
     * @return static
     */
    public function validationMethod($validationMethod)
    {
        return $this->addProperty('validation_method', $validationMethod);
    }

    /**
     * When set to true, the ignore_unmapped option will ignore an unmapped field
     * and will not match any documents for this query. Defaults to false.
     *
     * @param bool $ignoreUnmapped
     * @return static
     */
    public function ignoreUnmapped($ignoreUnmapped)
    {
        return $this->addProperty('ignore_unmapped', $ignoreUnmapped);
    }
}
