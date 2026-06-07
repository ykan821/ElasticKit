<?php

namespace ElasticKit\DSL\Queries\Geo;

use ElasticKit\DSL\Node;

/**
 * Matches geo_point and geo_shape values that intersect a grid cell from a GeoGrid aggregation.
 *
 * The query is designed to match the documents that fall inside a bucket of a geogrid aggregation
 * by providing the key of the bucket. For geohash and geotile grids, the query can be used for
 * geo_point and geo_shape fields. For geo_hex grid, it can only be used for geo_point fields.
 */
class GeoGrid extends Node
{
    protected $_key = 'geo_grid';

    protected $_isPropertyField = true;

    /**
     * The geohex grid key to match. Only usable with geo_point fields.
     *
     * @param string $geohex
     * @return static
     */
    public function geohex($geohex)
    {
        return $this->addProperty('geohex', $geohex);
    }

    /**
     * The geotile grid key to match (e.g. "6/32/21").
     * Usable with geo_point and geo_shape fields.
     *
     * @param string $geotile
     * @return static
     */
    public function geotile($geotile)
    {
        return $this->addProperty('geotile', $geotile);
    }

    /**
     * The geohash grid key to match (e.g. "u1").
     * Usable with geo_point and geo_shape fields.
     *
     * @param string $geohash
     * @return static
     */
    public function geohash($geohash)
    {
        return $this->addProperty('geohash', $geohash);
    }
}
