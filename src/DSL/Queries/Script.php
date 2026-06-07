<?php

namespace ElasticKit\DSL\Queries;

use ElasticKit\DSL\Node;

/**
 * Represents an inline or stored script used in Elasticsearch queries and aggregations.
 */
class Script extends Node
{
    protected $_key = 'script';

    /**
     * (Optional) The ID of a stored script.
     *
     * @param string $id
     * @return static
     */
    public function id($id)
    {
        return $this->addProperty('id', $id);
    }

    /**
     * (Optional) The script language. Defaults to painless.
     *
     * @param string $lang
     * @return static
     */
    public function lang($lang)
    {
        return $this->addProperty('lang', $lang);
    }

    /**
     * (Required) The inline script source to execute.
     *
     * @param string $source
     * @return static
     */
    public function source($source)
    {
        return $this->addProperty('source', $source);
    }

    /**
     * (Optional) Named parameters passed into the script.
     *
     * @param array<string, mixed> $params
     * @return static
     */
    public function params($params)
    {
        return $this->addProperty('params', $params);
    }
}
