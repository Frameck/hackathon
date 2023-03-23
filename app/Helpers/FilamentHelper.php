<?php

namespace App\Helpers;

use App\Settings\CompanySettings;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Foundation\Vite;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Konnco\FilamentImport\Actions\ImportAction;
use pxlrbt\FilamentEnvironmentIndicator\FilamentEnvironmentIndicator;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class FilamentHelper
{
    public static function registerConfigs(): void
    {
        DatePicker::configureUsing(function (DatePicker $datePicker) {
            $datePicker->displayFormat('d/m/Y');
        });

        Textarea::configureUsing(function (Textarea $textArea) {
            $textArea->columnSpan('full');
        });

        CreateAction::configureUsing(function (CreateAction $createAction) {
            $createAction->icon('heroicon-o-plus');
        });

        Action::configureUsing(function (Action $action) {
            $action->iconButton();
        });

        ExportBulkAction::configureUsing(function (ExportBulkAction $exportBulkAction) {
            $exportBulkAction->label(__('filament-admin.excel.bulk-action.label'))->color('success');
        });

        ImportAction::configureUsing(function (ImportAction $importAction) {
            $importAction->icon('heroicon-o-upload');
        });

        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title(__('filament-admin.notifications.validation.title'))
                ->body($exception->getMessage())
                ->danger()
                ->send();
        };

        FilamentEnvironmentIndicator::configureUsing(function (FilamentEnvironmentIndicator $indicator) {
            $indicator->color = fn () => match (app()->environment()) {
                'production' => '#006D77', // 00B9C9
                'staging' => '#D97706',
                default => '#2A6f97', // 01497C
            };
        }, isImportant: true);

        FilamentEnvironmentIndicator::configureUsing(function (FilamentEnvironmentIndicator $indicator) {
            $indicator->showBadge = fn () => true;
            $indicator->showBorder = fn () => false;
        }, isImportant: true);

        Filament::serving(function () {
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.css'),
            );

            $primaryColor = CompanySettings::make()->colors['primary'] ?? '#f97316';
            $secondaryColor = CompanySettings::make()->colors['secondary'] ?? '#3b82f6';
            $accentColor = CompanySettings::make()->colors['accent'] ?? '#a855f7';

            Filament::pushMeta([
                new HtmlString('<meta name="theme-primary-color" id="theme-primary-color" content="' . $primaryColor . '">' .
                    '<meta name="theme-secondary-color" id="theme-secondary-color" content="' . $secondaryColor . '">' .
                    '<meta name="theme-accent-color" id="theme-accent-color" content="' . $accentColor . '">'),
            ]);

            Column::macro('linkRecord', function ($view = 'edit') {
                /**
                 * @var \Filament\Tables\Columns\Column $this
                 */
                return $this->url(function ($record) use ($view) {
                    if ($record === null) {
                        return;
                    }

                    $selectedResource = null;
                    /**
                     * @var \Filament\Tables\Columns\Column $this
                     */
                    $relationship = str()->before($this->getName(), '.');
                    $relatedRecord = $record->{$relationship};

                    if ($relatedRecord === null) {
                        return;
                    }

                    foreach (Filament::getResources() as $resource) {
                        if ($relatedRecord instanceof ($resource::getModel())) {
                            $selectedResource = $resource;

                            break;
                        }
                    }

                    if (!$selectedResource) {
                        return;
                    }

                    return $selectedResource::getUrl($view, $relatedRecord->getKey());
                });
            });

            IconColumn::macro('toggle', function () {
                /**
                 * @var \Filament\Tables\Columns\IconColumn $this
                 */
                $this->action(function ($record, $column) {
                    $name = $column->getName();
                    $record->update([
                        $name => !$record->$name,
                    ]);
                });

                return $this;
            });
        });
    }
}
