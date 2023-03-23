<?php

namespace App\Exports;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class FilamentExcelExport extends ExcelExport implements WithStyles, WithTitle
{
    protected bool $formSchemaIsDefault = false;

    public function setUp(): void
    {
        $this->withFilename($this->getFilename());
        $this->withColumns([
            $this->getColumns(),
        ]);
    }

    public function query(): Builder
    {
        $query = parent::query();

        if ($this->formSchemaIsDefault) {
            $query->whereBetween(
                $this->getModelInstance()->getDateColumn(),
                $this->formData
            );
        }

        return $query;
    }

    public function form(array $formSchema): static
    {
        $this->formSchema = array_merge(
            $this->formSchema,
            $formSchema,
        );

        return $this;
    }

    public function setDefaultFormSchema(): static
    {
        $this->formSchemaIsDefault = true;

        $defaultFormSchema = [
            Grid::make()->schema([
                DatePicker::make('date_on')
                    ->default(now()->subDays(7))
                    ->label(__('filament-admin.excel.header-action.form.date_on')),
                DatePicker::make('date_off')
                    ->default(now())
                    ->label(__('filament-admin.excel.header-action.form.date_off')),
            ]),
        ];

        $this->formSchema = array_merge(
            $this->formSchema,
            $defaultFormSchema,
        );

        return $this;
    }

    public function getColumns(): array
    {
        if (!$this->modelInstance) {
            return [];
        }

        $modelInstance = $this->getModelInstance();
        $exportColumns = $modelInstance->getExportColumns();

        if (empty($exportColumns)) {
            return config('filament-admin.excel.export_from_table')
                ? $this->createFieldMappingFromTable()->toArray()
                : $this->createColumnsArray($modelInstance->getFillable());
        }

        return $this->createColumnsArray($exportColumns);
    }

    public function getFilename(): string
    {
        if (!$this->modelInstance) {
            return '';
        }

        return $this->ensureFilenameHasExtension(
            now()->format('d-m-Y') . '-' . $this->getModelInstance()->getTable()
        );
    }

    protected function createColumnsArray(array $exportColumns): array
    {
        return collect($exportColumns)->map(function ($column) {
            $columnValue = $column['value'] ?? $column;
            $columnLabel = $column['label'] ?? null;
            $sumValuesFromRelationship = $column['sum_values'] ?? false;

            $columnComponent = Column::make($columnValue);
            $columnComponent = str($columnValue)
                ->whenContains(
                    needles: '.',
                    callback: fn (Stringable $relationPath): Column => $this->resolveRelationship($columnComponent, $relationPath, $sumValuesFromRelationship),
                    default: fn (): Column => $columnComponent
                );

            if ($columnLabel) {
                $columnComponent = $this->addColumnHeading($columnComponent, $columnLabel); // intellisense gives error because can't resolve the corret type
            }

            if (!$columnLabel && str($columnValue)->contains('.')) {
                $columnComponent = $this->addColumnHeading($columnComponent, $columnValue);
            }

            return $columnComponent;
        })->toArray();
    }

    protected function resolveRelationship(Column $column, Stringable $relationPath, bool $sumValues): Column
    {
        $this->getModelInstance()->preventLazyLoading(false);

        return $column
            ->formatStateUsing(function ($record) use ($relationPath, $sumValues) {
                $pathToLoadRelationFrom = $relationPath->beforeLast('.')->toString();
                $keyToGetFromRelation = $relationPath->afterLast('.')->toString();
                $recordWithRelation = $record->load($pathToLoadRelationFrom)->toArray();

                $relationObject = Arr::get($recordWithRelation, $pathToLoadRelationFrom);

                if (is_array($relationObject)) {
                    return collect($relationObject)
                        ->when(
                            value: $sumValues,
                            callback: fn (Collection $relationObject) => $relationObject->sum($keyToGetFromRelation),
                            default: function (Collection $relationObject) use ($keyToGetFromRelation) {
                                return $relationObject
                                    ->pluck($keyToGetFromRelation)
                                    ->implode(config('filament-admin.excel.concatenate_relations_with'));
                            }
                        );
                }

                return $relationObject[$keyToGetFromRelation];
            });
    }

    protected function addColumnHeading(Column $column, string $columnLabel): Column
    {
        $heading = str($columnLabel)
            ->whenContains(
                needles: '.',
                callback: function (Stringable $columnLabel) {
                    $columnLabel = $columnLabel
                        ->explode('.')
                        ->pop(2)
                        ->reverse()
                        ->implode('_');

                    return str($columnLabel)->headline();
                },
                default: fn (Stringable $columnLabel) => $columnLabel->title()
            );

        return $column->heading($heading);
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return str(
            $this->getFilename()
        )->match('/[a-z]+/');
    }
}
