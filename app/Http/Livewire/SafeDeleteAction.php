<?php

namespace App\Http\Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Actions\Concerns\CanCustomizeProcess;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;

class SafeDeleteAction extends DeleteAction
{
    use CanCustomizeProcess;

    // protected $listeners = [
    //     'safeDelete' => 'safeDelete'
    // ];

    public static function getDefaultName(): ?string
    {
        return 'safe-delete';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->form([
            TextInput::make('name')
                ->required(),
        ]);

        $this->modalSubheading('Please type the name/title to confirm you want to delete this record');

        $this->action(function () {
            $result = $this->process(function (Model $record, array $data) {
                $this->safeDelete($record, $data);
            });

            if (!$result) {
                $this->failure();

                return;
            }

            $this->success();
        });
    }

    public function safeDelete(Model $record, array $data): mixed
    {
        $slugOrigin = (new $record)->getSlugOrigin();
        if (is_array($slugOrigin)) {
            $slugOrigin = $slugOrigin[0];
        }

        $notification = Notification::make();

        if ($record->$slugOrigin == $data['name']) {
            $notification
                ->title(__('filament-support::actions/delete.single.messages.deleted'))
                ->success()
                ->duration(5000)
                ->send();

            return $record->delete();
        }

        $this->successNotification(null);

        return $notification
            ->title('Error while deleting ' . $record->$slugOrigin)
            ->body('Inserted value does not match ' . $slugOrigin . ' attribute on model')
            ->danger()
            ->duration(5000)
            ->send();
    }
}
