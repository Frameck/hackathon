<?php

namespace App\Filament\Resources;

use App\Exports\FilamentExcelExport;
use App\Filament\Resources\ChatMessageResource\Pages;
use App\Models\ChatMessage;
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

class ChatMessageResource extends Resource
{
    use HasModelLabelAndRecordTitleAttribute;

    protected static ?string $model = ChatMessage::class;

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
                Forms\Components\DateTimePicker::make('timestamp'),
                Forms\Components\DatePicker::make('date'),
                Forms\Components\Textarea::make('raw_message')
                    ->maxLength(65535),
                Forms\Components\Textarea::make('filtered_message')
                    ->maxLength(65535),
                Forms\Components\Toggle::make('filtered'),
                Forms\Components\TextInput::make('filtered_content'),
                Forms\Components\TextInput::make('risk'),
                Forms\Components\TextInput::make('filter_detected_language')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_family_friendly'),
                Forms\Components\TextInput::make('general_risk'),
                Forms\Components\TextInput::make('bullying'),
                Forms\Components\TextInput::make('violence'),
                Forms\Components\TextInput::make('relationship_sexual_content'),
                Forms\Components\TextInput::make('vulgarity'),
                Forms\Components\TextInput::make('drugs_alcohol'),
                Forms\Components\TextInput::make('in_app'),
                Forms\Components\TextInput::make('alarm'),
                Forms\Components\TextInput::make('fraud'),
                Forms\Components\TextInput::make('hate_speech'),
                Forms\Components\TextInput::make('religious'),
                Forms\Components\TextInput::make('website'),
                Forms\Components\TextInput::make('child_grooming'),
                Forms\Components\TextInput::make('public_threat'),
                Forms\Components\TextInput::make('extremism'),
                Forms\Components\TextInput::make('subversive'),
                Forms\Components\TextInput::make('sentiment'),
                Forms\Components\TextInput::make('politics'),
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
                Tables\Columns\TextColumn::make('timestamp')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('date')
                    ->date(),
                Tables\Columns\TextColumn::make('raw_message'),
                Tables\Columns\TextColumn::make('filtered_message'),
                Tables\Columns\IconColumn::make('filtered')
                    ->boolean(),
                Tables\Columns\TextColumn::make('filtered_content'),
                Tables\Columns\TextColumn::make('risk'),
                Tables\Columns\TextColumn::make('filter_detected_language'),
                Tables\Columns\IconColumn::make('is_family_friendly')
                    ->boolean(),
                Tables\Columns\TextColumn::make('general_risk'),
                Tables\Columns\TextColumn::make('bullying'),
                Tables\Columns\TextColumn::make('violence'),
                Tables\Columns\TextColumn::make('relationship_sexual_content'),
                Tables\Columns\TextColumn::make('vulgarity'),
                Tables\Columns\TextColumn::make('drugs_alcohol'),
                Tables\Columns\TextColumn::make('in_app'),
                Tables\Columns\TextColumn::make('alarm'),
                Tables\Columns\TextColumn::make('fraud'),
                Tables\Columns\TextColumn::make('hate_speech'),
                Tables\Columns\TextColumn::make('religious'),
                Tables\Columns\TextColumn::make('website'),
                Tables\Columns\TextColumn::make('child_grooming'),
                Tables\Columns\TextColumn::make('public_threat'),
                Tables\Columns\TextColumn::make('extremism'),
                Tables\Columns\TextColumn::make('subversive'),
                Tables\Columns\TextColumn::make('sentiment'),
                Tables\Columns\TextColumn::make('politics'),
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
            'index' => Pages\ListChatMessages::route('/'),
            'create' => Pages\CreateChatMessage::route('/create'),
            'edit' => Pages\EditChatMessage::route('/{record}/edit'),
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
