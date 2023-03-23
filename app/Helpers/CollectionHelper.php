<?php

namespace App\Helpers;

use App\Contracts\ProvidesMacros;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class CollectionHelper implements ProvidesMacros
{
    public static function paginate(
        Collection $results,
        ?int $perPage = null,
        array $columns = ['*'],
        string $pageName = 'page',
        ?int $page = null
    ): LengthAwarePaginator {
        $pageNumber = $page ?: Paginator::resolveCurrentPage('page');

        $total = $results->count();

        return static::paginator(
            $results->forPage($pageNumber, $perPage),
            $total,
            $perPage,
            $pageNumber,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    protected static function paginator(
        Collection $items,
        int $total,
        int $perPage,
        int $currentPage,
        array $options
    ): LengthAwarePaginator {
        return app()->make(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }

    public static function registerMacros(): void
    {
        Collection::macro(
            'paginate',
            fn (
                ?int $perPage = null,
                array $columns = ['*'],
                string $pageName = 'page',
                ?int $page = null
            ) => (
                CollectionHelper::paginate(
                    $this,
                    $perPage,
                )
            )
        );
    }
}
