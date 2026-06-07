<?php

namespace ElasticKit\DSL\Queries\Compound\Functions;

use ElasticKit\DSL\Node;
use ElasticKit\DSL\Queries\Script;

/**
 * Wraps another query and customizes the scoring using a script expression.
 */
class ScriptScore extends Node
{
    protected $_key = 'script_score';

    /**
     * (Required) The script used to compute the custom score.
     *
     * @param mixed $script
     * @return static
     */
    public function script($script)
    {
        return $this->addProperty('script', Script::create($script));
    }
}
