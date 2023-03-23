<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->label(__('filament-admin.excel.import.label'))
                ->fields([
                    ImportField::make('name'),
                    ImportField::make('email'),
                    ImportField::make('password'),
                ])
                ->mutateBeforeCreate(function ($row) {
                    $row['password'] = Hash::make($row['password']);

                    return $row;
                }),
        ];
    }
}
