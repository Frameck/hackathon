<?php

namespace App\Helpers;

use App\Contracts\ProvidesMacros;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;

class QueryHelper implements ProvidesMacros
{
    public static function getSqlQueryWithBindings(QueryExecuted $query): string
    {
        return static::getRawSqlQueryWithBindings(
            $query->sql,
            $query->bindings
        );
    }

    public static function getEloquentSqlQueryWithBindings(EloquentBuilder $query): string
    {
        return static::getRawSqlQueryWithBindings(
            $query->toSql(),
            $query->getBindings()
        );
    }

    public static function getRawSqlQueryWithBindings(string $sql, array $bindings): string
    {
        return Str::replaceArray(
            '?',
            collect($bindings)
                ->map(function (mixed $binding) {
                    if (is_numeric($binding)) {
                        return $binding;
                    }

                    if (is_bool($binding)) {
                        return ($binding) ? 'true' : 'false';
                    }

                    return "'{$binding}'";
                })
                ->toArray(),
            $sql,
        );
    }

    public static function registerMacros(): void
    {
        EloquentBuilder::macro('whereRelationIn', function ($relation, $column, $array) {
            $this->whereHas(
                $relation,
                fn ($q) => $q->whereIn($column, $array)
            );
        });

        EloquentBuilder::macro('whereLike', function (array|string $columns, string $value) {
            if (is_string($columns)) {
                return $this->where($columns, 'like', '%' . $value . '%');
            }

            foreach ($columns as $key => $column) {
                $this->orWhere($column, 'like', '%' . $value . '%');
            }
        });

        EloquentBuilder::macro('toRawSql', fn () => (
            QueryHelper::getEloquentSqlQueryWithBindings($this)
        ));

        QueryBuilder::macro('toRawSql', fn () => (
            QueryHelper::getEloquentSqlQueryWithBindings($this)
        ));
    }
}
