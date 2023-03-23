<?php

namespace App\Filament\Resources;

use App\Exports\FilamentExcelExport;
use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use App\Traits\HasModelLabelAndRecordTitleAttribute;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AccountResource extends Resource
{
    use HasModelLabelAndRecordTitleAttribute;

    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = '';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('created_by'),
                Forms\Components\TextInput::make('updated_by'),
                Forms\Components\TextInput::make('account_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('alliance_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('session_count'),
                Forms\Components\TextInput::make('session_duration'),
                Forms\Components\TextInput::make('transaction_count'),
                Forms\Components\TextInput::make('revenue'),
                Forms\Components\DatePicker::make('date'),
                Forms\Components\Toggle::make('active'),
                Forms\Components\TextInput::make('account_state'),
                Forms\Components\DatePicker::make('last_active_date'),
                Forms\Components\TextInput::make('level'),
                Forms\Components\TextInput::make('created_language')
                    ->maxLength(255),
                Forms\Components\TextInput::make('created_country_code')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('created_time'),
                Forms\Components\TextInput::make('session_count_today'),
                Forms\Components\TextInput::make('session_duration_today'),
                Forms\Components\TextInput::make('transaction_count_today'),
                Forms\Components\TextInput::make('revenue_today'),
                Forms\Components\TextInput::make('last_login_game_client_language')
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_login_country_code')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('updated_by'),
                Tables\Columns\TextColumn::make('account_id'),
                Tables\Columns\TextColumn::make('alliance_id'),
                Tables\Columns\TextColumn::make('session_count'),
                Tables\Columns\TextColumn::make('session_duration'),
                Tables\Columns\TextColumn::make('transaction_count'),
                Tables\Columns\TextColumn::make('revenue'),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('account_state'),
                Tables\Columns\TextColumn::make('last_active_date')
                    ->date(),
                Tables\Columns\TextColumn::make('level'),
                Tables\Columns\TextColumn::make('created_language'),
                Tables\Columns\TextColumn::make('created_country_code'),
                Tables\Columns\TextColumn::make('created_time')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('session_count_today'),
                Tables\Columns\TextColumn::make('session_duration_today'),
                Tables\Columns\TextColumn::make('transaction_count_today'),
                Tables\Columns\TextColumn::make('revenue_today'),
                Tables\Columns\TextColumn::make('last_login_game_client_language'),
                Tables\Columns\TextColumn::make('last_login_country_code'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ], layout: Layout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                ExportBulkAction::make()
                    ->exports([
                        FilamentExcelExport::make(),
                    ]),
            ])
            ->headerActions([
                ExportAction::make('export_with_dates')
                    ->exports([
                        FilamentExcelExport::make('modal')
                            ->setDefaultFormSchema(),
                    ])
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
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
