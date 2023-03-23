<?php

namespace App\Traits;

use function App\Helpers\in_array_all;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait HasHelperFunctionsAndScopes
{
    public static function getTableName(): string
    {
        return app(static::class)->getTable();
    }

    public static function getResourceLabel(): string
    {
        return str(static::class)
            ->classBasename()
            ->snake()
            ->lower()
            ->toString();
    }

    public static function getTableColumns(): array
    {
        return Schema::getColumnListing(
            static::getTableName()
        );
    }

    // public function getTableColumns(): array
    // {
    //     return Schema::getColumnListing(static::getTableName());
    // }

    public function getTableFillableColumns(): array
    {
        $columnsToExclude = [
            'id',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
        $columns = static::getTableColumns();

        return array_diff($columns, $columnsToExclude);
    }

    public function getColumnType(string $column): string
    {
        return Schema::getColumnType(static::getTableName(), $column);
    }

    public function getSlugOrigin(): string|array
    {
        $columns = static::getTableColumns();
        $possibilities = [
            'nome',
            'titolo',
            'name',
            'title',
        ];

        foreach ($possibilities as $key => $value) {
            if (in_array_all(['first_name', 'last_name'], $columns)) {
                return ['first_name', 'last_name'];
            }
            if (in_array_all(['name', 'surname'], $columns)) {
                return ['name', 'surname'];
            }
            if (in_array_all(['nome', 'cognome'], $columns)) {
                return ['nome', 'cognome'];
            }
            if (in_array($value, $columns)) {
                return $value;
            }
        }
    }

    public function getActiveColumn(): string
    {
        $columns = static::getTableColumns();
        $possibilities = [
            'active',
            'attivo',
        ];

        foreach ($possibilities as $key => $value) {
            if (in_array($value, $columns)) {
                return $value;
            }
        }
    }

    public function getActiveConditionsColumns(): string|array
    {
        $columns = static::getTableColumns();
        $possibilities = [
            'active',
            'attivo',
            'date_on',
            'date_off',
            'date_start',
            'date_end',
            'data_inizio',
            'data_fine',
        ];

        foreach ($possibilities as $key => $value) {
            if (in_array_all(['active', 'date_on', 'date_off'], $columns)) {
                return [
                    'active' => 'active',
                    'date_on' => 'date_on',
                    'date_off' => 'date_off',
                ];
            }
            if (in_array_all(['attivo', 'data_inizio', 'data_fine'], $columns)) {
                return [
                    'active' => 'attivo',
                    'date_on' => 'data_inizio',
                    'date_off' => 'data_fine',
                ];
            }
            if (in_array($value, $columns)) {
                return $value;
            }
        }
    }

    public function getDateColumn(): string
    {
        $columns = static::getTableColumns();
        $possibilities = [
            'data',
            'date',
            'created_at',
        ];

        foreach ($possibilities as $key => $value) {
            if (in_array($value, $columns)) {
                return $value;
            }
        }
    }

    // SCOPES
    /**
     * Filtra per colonna active/attivo
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(static::getTableName() . $this->getActiveColumn(), true);
    }

    /**
     * Filtra per colonna active/attivo e per data inizio e data fine
     */
    public function scopeActiveWithDates(Builder $query): Builder
    {
        $columns = $this->getActiveConditionsColumns();

        return $query
            ->where(static::getTableName() . $columns['active'], true)
            ->where(static::getTableName() . $columns['date_on'], '<=', now())
            ->where(static::getTableName() . $columns['date_off'], '>=', now());
    }

    // public function scopeWhereLike(Builder $query, string $column, string $value): Builder
    // {
    //     return $query->where(static::getTableName() . $column, 'like', '%' . $value . '%');
    // }

    public function scopeBetweenDates(Builder $query, string $date_start, string $date_end, ?string $column = null): Builder
    {
        $dateColumn = $column ?: $this->getDateColumn();

        return $query
            ->where(static::getTableName() . $dateColumn, '<=', Carbon::parse($date_start))
            ->where(static::getTableName() . $dateColumn, '>=', Carbon::parse($date_end));
    }
}
