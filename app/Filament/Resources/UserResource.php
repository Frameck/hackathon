<?php

namespace App\Filament\Resources;

use App\Exports\FilamentExcelExport;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Http\Livewire\SafeDeleteAction;
use App\Models\User;
use App\Traits\HasModelLabelAndRecordTitleAttribute;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use STS\FilamentImpersonate\Impersonate;

class UserResource extends Resource
{
    use HasModelLabelAndRecordTitleAttribute;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Filament Shield';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $testingUser = User::getTestingUser();

        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('first_name')
                                ->required(),
                            TextInput::make('last_name')
                                ->required(),
                            TextInput::make('email')
                                ->required()
                                ->email()
                                ->unique(table: User::class, ignorable: fn (?User $record): ?User => $record),
                        ]),
                        TextInput::make('password')
                            ->password()
                            ->maxLength(255)
                            ->required(fn (?User $record) => $record === null)
                            ->disabled(fn (?User $record) => (
                                $record
                                    ? $record->id === $testingUser->id
                                    : false
                            ))
                            ->dehydrated(fn ($state) => !empty($state)),
                        TextInput::make('passwordConfirmation')
                            ->password()
                            ->maxLength(255)
                            ->same('password')
                            ->dehydrated(false)
                            ->disabled(fn (?User $record) => (
                                $record
                                    ? $record->id === $testingUser->id
                                    : false
                            )),
                        CheckboxList::make('roles')
                            ->relationship('roles', 'name'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TagsColumn::make('roles.name'),
                TextColumn::make('last_login')
                    ->dateTime('d-m-Y H:i:s'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d-m-Y H:i:s'),
            ])
            ->actions([
                Impersonate::make('impersonate')
                    ->requiresConfirmation(true),
                EditAction::make(),
                // DeleteAction::make(),
                SafeDeleteAction::make(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple(),
                TernaryFilter::make('email_verified_at')
                    ->nullable(),
                TrashedFilter::make(),
            ], layout: Layout::AboveContent)
            ->bulkActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ExportBulkAction::make()
                    ->exports([
                        FilamentExcelExport::make(),
                    ]),
            ])
            ->prependActions([

            ])
            ->headerActions([
                ExportAction::make('export_with_dates')
                    ->exports([
                        FilamentExcelExport::make('modal')
                            ->setDefaultFormSchema(),
                    ])
                    ->label(__('filament-admin.excel.header-action.label'))
                    ->modalHeading(__('filament-admin.excel.header-action.label'))
                    ->color('success'),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'roles',
            ])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
