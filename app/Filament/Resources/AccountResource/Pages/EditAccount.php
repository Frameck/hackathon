<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditAccount extends EditRecord
{
    protected static string $resource = AccountResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getSaveAndCloseFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSaveAndCloseFormAction(): Action
    {
        return Action::make('saveAndClose')
            ->label(__('filament-admin.buttons.save_and_close'))
            ->color('secondary')
            ->action('save')
            ->after(fn () => redirect(static::getResource()::getUrl('index')));
    }
}
