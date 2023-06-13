<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SortByTranslation implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $params = explode('#', $property);
        $locale = $params[1];
        [$relationName, $columnName] = explode(".", $params[0]);

        $relation = $query->getRelation($relationName);
        $subQuery = $relation
            ->getQuery()
            ->select($columnName)
            ->where('locale', $locale)
            ->whereColumn($relation->getQualifiedForeignKeyName(), $relation->getQualifiedParentKeyName());

        $query->orderBy($subQuery, $descending ? "desc" : "asc");
    }
}
