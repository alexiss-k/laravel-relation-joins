<?php

namespace Reedware\LaravelRelationJoins\Relations;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany as Relation;

abstract class HasOneOrMany extends Relation
{
    /**
     * Adds the constraints for a relationship join.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Builder  $parentQuery
     * @param  string  $type
     * @param  string|null  $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRelationJoinQuery(Builder $query, Builder $parentQuery, $type = 'inner', $alias = null)
    {
        if (is_null($alias) && $query->getQuery()->from == $parentQuery->getQuery()->from) {
            $alias = $this->getRelationCountHash();
        }

        if (! is_null($alias) && $alias != $query->getModel()->getTable()) {
            $query->from($query->getModel()->getTable().' as '.$alias);

            $query->getModel()->setTable($alias);
        }

        return $query->whereColumn(
            $query->qualifyColumn($this->getForeignKeyName()), '=', $this->getQualifiedParentKeyName()
        );
    }
}