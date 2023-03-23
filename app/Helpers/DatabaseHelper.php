<?php

namespace App\Helpers;

use App\Traits\HasMakeConstructor;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Schema as DoctrineSchema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\BigIntType;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\SmallIntType;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\TextType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class DatabaseHelper
{
    use HasMakeConstructor;

    public function __construct(
        public ?string $modelClass = null,
        public ?string $tableName = null,
        public ?Model $model = null,
    ) {
    }

    public static function make(string $modelClass): static
    {
        return app(static::class, [
            'modelClass' => $modelClass,
            'tableName' => app($modelClass)::getTableName(),
            'model' => app($modelClass),
        ]);
    }

    public static function registerListener(): void
    {
        DB::listen(fn (QueryExecuted $query) => (
            Log::info(
                'Executed query',
                [
                    'sql' => QueryHelper::getSqlQueryWithBindings($query),
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                ]
            )
        ));
    }

    public function getSchemaDetails(): DoctrineSchema
    {
        return Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->introspectSchema();
    }

    public function getTableDetails(): Table
    {
        return Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->introspectTable($this->model->getTable());
    }

    public function getTableColumns(): array
    {
        return $this->getTableDetails($this->modelClass)->getColumns();
    }

    public function getTableIndexes(): array
    {
        return $this->getTableDetails($this->modelClass)->getIndexes();
    }

    public function getTableUniqueIndexesColumnsNames(): array
    {
        $columns = [];
        foreach ($this->getTableIndexes($this->modelClass) as $index) {
            if (!str($index->getName())->contains('unique')) {
                continue;
            }

            $columns[] = $index->getColumns();
        }

        return Arr::flatten($columns);
    }

    public function getValidationRules(): array
    {
        $uniqueIndexesOnCurrentTable = $this->getTableUniqueIndexesColumnsNames($this->modelClass);
        $validationRules = [];

        foreach ($this->getTableColumns($this->modelClass) as $column) {
            $columnName = str($column->getName());
            if ($columnName->is([
                'id',
                'slug',
                '*_token',
                'created_by',
                'updated_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ])) {
                continue;
            }

            $validationRules[$column->getName()] = $this->getColumnValidationRules($column);

            if (in_array($column->getName(), $uniqueIndexesOnCurrentTable)) {
                $validationRules[$column->getName()][] = 'unique:' . $this->tableName . ',' . $column->getName();
            }
        }

        return $validationRules;
    }

    public function getColumnValidationRules(Column $column): array
    {
        $rules = match ($column->getType()::class) {
            StringType::class => [
                'string',
                'max:' . $column->getLength(),
            ],
            TextType::class => [
                'string',
                'max:' . $column->getLength(),
            ],
            JsonType::class => [
                'json',
            ],
            BooleanType::class => [
                'boolean',
            ],
            IntegerType::class => [
                'integer',
                'numeric',
            ],
            FloatType::class => [
                'decimal:' . $column->getScale(),
            ],
            SmallIntType::class => [
                'integer',
                'numeric',
            ],
            BigIntType::class => [
                'integer',
                'numeric',
            ],
            DateType::class => [
                'date_format:' . DB_DATE_FORMAT,
            ],
            DateTimeType::class => [
                'date_format:' . DB_DATETIME_FORMAT,
            ],
            default => [],
        };

        // check if not nullable
        if ($column->getNotnull()) {
            // if doesn't have a default value add required rule
            if (blank($column->getDefault())) {
                $rules[] = 'required';
            }
        } else {
            $rules[] = 'nullable';
        }

        $columnName = str($column->getName());
        if ($columnName->endsWith('_id')) {
            $relatedTable = str($columnName->before('_id'))->plural();
            $rules[] = $relatedTable->prepend('exists:')->append(',id')->toString();
        }

        if ($columnName->contains('uuid')) {
            $rules[] = 'uuid';
        }

        if ($columnName->contains('email')) {
            $rules[] = 'email';
        }

        if ($columnName->contains('password')) {
            $rules[] = 'current_password';
        }

        return $rules;
    }
}
