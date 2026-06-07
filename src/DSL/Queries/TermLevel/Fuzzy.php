<?php

namespace ElasticKit\DSL\Queries\TermLevel;

use ElasticKit\DSL\Node;

class Fuzzy extends Node
{
    protected $_key = 'fuzzy';

    protected $_isPropertyField = true;

    /**
     * Term you wish to find in the provided <field>.
     *
     * @param string $value
     * @return static
     */
    public function value($value)
    {
        return $this->addProperty('value', $value);
    }

    /**
     * Maximum edit distance allowed for matching. See Fuzziness for valid values and more information.
     *
     * @param string $fuzziness
     * @return static
     */
    public function fuzziness($fuzziness)
    {
        return $this->addProperty('fuzziness', $fuzziness);
    }

    /**
     * Maximum number of variations created. Defaults to 50.
     *
     * @param int $maxExpansions
     * @return static
     */
    public function maxExpansions($maxExpansions)
    {
        return $this->addProperty('max_expansions', $maxExpansions);
    }

    /**
     * Number of beginning characters left unchanged when creating expansions. Defaults to 0.
     *
     * @param int $prefixLength
     * @return static
     */
    public function prefixLength($prefixLength)
    {
        return $this->addProperty('prefix_length', $prefixLength);
    }

    /**
     * Indicates whether edits include transpositions of two adjacent characters (ab → ba). Defaults to true.
     *
     * @param bool $transpositions
     * @return static
     */
    public function transpositions($transpositions)
    {
        return $this->addProperty('transpositions', $transpositions);
    }

    /**
     * Method used to rewrite the query. For valid values and more information, see the rewrite parameter.
     *
     * @param string $rewrite
     * @return static
     */
    public function rewrite($rewrite)
    {
        return $this->addProperty('rewrite', $rewrite);
    }
}
