<?php

namespace App\Filament\Resources\ChatMessageResource\Pages;

use App\Filament\Resources\ChatMessageResource;
use Closure;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ListChatMessages extends ListRecords
{
    protected static string $resource = ChatMessageResource::class;

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
