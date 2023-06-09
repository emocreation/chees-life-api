<?php

namespace App\Sorts;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortByRelation implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        [$relationName, $columnName] = explode(".", $property);
        $relation = $query->getRelation($relationName);
        $subQuery = $relation
            ->getQuery()
            ->select($columnName)
            ->whereColumn($relation->getQualifiedForeignKeyName(), $relation->getQualifiedParentKeyName());
        $query->orderBy($subQuery, $descending ? "desc" : "asc");
    }
}
