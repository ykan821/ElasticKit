<?php

namespace ElasticKit\DSL\Params;

use ElasticKit\DSL\Node;

/**
 * Search suggestions based on term, completion, or phrase suggesters.
 *
 * @phpstan-consistent-constructor
 */
class Suggest extends Node
{
    protected $_key = 'suggest';

    /**
     * Add a term suggester.
     *
     * @param string $alias
     * @param string $field
     * @param string|null $text
     * @return static
     */
    public function term($alias, $field, $text = null)
    {
        $suggest = ['term' => ['field' => $field]];
        if ($text !== null) {
            $suggest['text'] = $text;
        }
        return $this->addProperty($alias, $suggest);
    }

    /**
     * Add a completion suggester.
     *
     * @param string $alias
     * @param string $field
     * @param string|null $prefix
     * @return static
     */
    public function completion($alias, $field, $prefix = null)
    {
        $suggest = ['completion' => ['field' => $field]];
        if ($prefix !== null) {
            $suggest['prefix'] = $prefix;
        }
        return $this->addProperty($alias, $suggest);
    }

    /**
     * Add a phrase suggester.
     *
     * @param string $alias
     * @param string $field
     * @param string|null $text
     * @return static
     */
    public function phrase($alias, $field, $text = null)
    {
        $suggest = ['phrase' => ['field' => $field]];
        if ($text !== null) {
            $suggest['text'] = $text;
        }
        return $this->addProperty($alias, $suggest);
    }
}
