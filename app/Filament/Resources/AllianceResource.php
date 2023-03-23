<?php

namespace App\Filament\Resources;

use App\Exports\FilamentExcelExport;
use App\Filament\Resources\AllianceResource\Pages;
use App\Models\Alliance;
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

class AllianceResource extends Resource
{
    use HasModelLabelAndRecordTitleAttribute;

    protected static ?string $model = Alliance::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = '';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('created_by'),
                Forms\Components\TextInput::make('updated_by'),
                Forms\Components\TextInput::make('alliance_id')
                    ->maxLength(255),
                Forms\Components\Toggle::make('family_friendly'),
                Forms\Components\DatePicker::make('date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_by'),
                Tables\Columns\TextColumn::make('updated_by'),
                Tables\Columns\TextColumn::make('alliance_id'),
                Tables\Columns\IconColumn::make('family_friendly')
                    ->boolean(),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
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
            'index' => Pages\ListAlliances::route('/'),
            'create' => Pages\CreateAlliance::route('/create'),
            'edit' => Pages\EditAlliance::route('/{record}/edit'),
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
