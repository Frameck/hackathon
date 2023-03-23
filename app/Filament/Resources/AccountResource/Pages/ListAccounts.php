<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Imports\AccountImport;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class ListAccounts extends ListRecords
{
    protected static string $resource = AccountResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->fields([
                    // ImportField::make(''),
                ])
                ->mutateBeforeCreate(function ($row) {
                    // $row['field'] = str($row['field'])->lower();

                    // return $row;
                }),
            Action::make('Import xlsx')
                ->icon('heroicon-o-document-add')
                ->form([
                    FileUpload::make('excel_file')
                        ->directory('import'),
                ])
                ->action(function (array $data) {
                    FacadesExcel::import(new AccountImport, storage_path('/app/public/' . $data['excel_file']));
                }),
        ];
    }

    protected function getTableRecordUrlUsing(): ?Closure
    {
        $routeFragment = str($this->getModel())->classBasename()->snake('-')->plural()->toString();

        return fn (Model $record): string => route("filament.resources.{$routeFragment}.edit", ['record' => $record]);
    }

    protected function getTableRecordActionUsing(): ?Closure
    {
        return fn (): ?string => null;
    }
}
