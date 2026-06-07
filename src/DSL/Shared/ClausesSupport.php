<?php

namespace ElasticKit\DSL\Shared;

use Closure;
use ElasticKit\DSL\Query;

/**
 * Provides deferred clause merging for compound query nodes.
 *
 * Accumulates clauses added via pushClause() method,
 * then merges them into _properties during serialization.
 */
trait ClausesSupport
{
    /**
     * Clauses keyed by property name, merged at serialization time.
     *
     * @var array<string, array<int, mixed>>
     */
    protected $_clauses = [];

    /**
     * Push a clause for the given property.
     *
     * @param string $key Property name (e.g. 'must', 'clauses', 'queries')
     * @param mixed $clause
     * @return static
     */
    protected function pushClause(string $key, $clause)
    {
        $this->_clauses[$key][] = $clause;
        return $this;
    }

    /**
     * Serialize to array, merging clauses into the final output.
     *
     * @return array<string, mixed>
     */
    public function toArray()
    {
        $this->mergeClauses();
        return parent::toArray();
    }

    /**
     * Merge clauses into _properties, adding each clause
     * directly to the target container's clause list.
     *
     * @return void
     */
    private function mergeClauses()
    {
        foreach ($this->_clauses as $key => $clauses) {
            if (!isset($this->_properties[$key])) {
                $this->_properties[$key] = (new Query())->multi(true);
            }
            $target = $this->_properties[$key];
            if ($target instanceof Query) {
                foreach ($clauses as $clause) {
                    $resolved = ($clause instanceof Closure) ? Query::create($clause) : $clause;
                    $target->addQuery($resolved);
                }
            }
        }
        $this->_clauses = [];
    }
}
