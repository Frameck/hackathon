<?php

namespace App\Filament\Pages;

use App\Helpers\MailHelper;
use App\Settings\CompanySettings;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class ManageCompany extends SettingsPage
{
    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?int $navigationSort = -1;

    protected static ?string $slug = 'company-settings';

    protected static string $settings = CompanySettings::class;

    protected static function getNavigationLabel(): string
    {
        return __('filament-admin.settings.pages.company.navigation-label');
    }

    protected function getHeading(): string
    {
        return match (app()->getLocale()) {
            'en' => CompanySettings::make()->name . ' ' . __('filament-admin.settings.pages.company.heading'),
            'it' => __('filament-admin.settings.pages.company.heading') . ' ' . CompanySettings::make()->name,
            default => CompanySettings::make()->name . ' ' . __('filament-admin.settings.pages.company.heading'),
        };
    }

    protected function getSubHeading(): string
    {
        return __('filament-admin.settings.pages.company.subheading');
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->columnSpan(2),
            Toggle::make('active')
                ->columnSpan(2),
            Tabs::make('Settings')
                ->columnSpan(2)
                ->schema([
                    Tab::make(__('filament-admin.settings.pages.company.tabs.company_registry'))
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Grid::make()->schema([
                                Grid::make(4)->schema([
                                    TextInput::make('address'),
                                    TextInput::make('city'),
                                    TextInput::make('state')
                                        ->maxLength(2)
                                        ->dehydrateStateUsing(fn ($state) => mb_strtoupper($state)),
                                    TextInput::make('country')
                                        ->maxLength(2)
                                        ->dehydrateStateUsing(fn ($state) => mb_strtoupper($state)),
                                ]),
                                TextInput::make('phone')
                                    ->columnSpan(2),
                            ]),
                        ]),
                    Tab::make(__('filament-admin.settings.pages.company.tabs.email'))
                        ->icon('heroicon-o-at-symbol')
                        ->badge(function ($state) {
                            return count(
                                Arr::flatten($state['emails'])
                            );
                        })
                        ->schema([
                            Tabs::make('Emails')->schema([
                                Tab::make('Info')
                                    ->badge(fn ($state) => count($state['emails']['info']))
                                    ->schema([
                                        TableRepeater::make('emails.info')
                                            ->headers(['Email'])
                                            ->label(false)
                                            ->createItemButtonLabel(__('filament-admin.settings.pages.company.repeater.email'))
                                            ->schema([
                                                TextInput::make('mail')
                                                    ->label(false)
                                                    ->email(fn ($state) => !empty($state)),
                                            ]),
                                    ]),
                                Tab::make('Customer Care')
                                    ->badge(fn ($state) => count($state['emails']['customer_care']))
                                    ->schema([
                                        TableRepeater::make('emails.customer_care')
                                            ->headers(['Email'])
                                            ->label(false)
                                            ->createItemButtonLabel(__('filament-admin.settings.pages.company.repeater.email'))
                                            ->schema([
                                                TextInput::make('mail')
                                                    ->label(false)
                                                    ->email(fn ($state) => !empty($state)),
                                            ]),
                                    ]),
                                Tab::make('Administration')
                                    ->badge(fn ($state) => count($state['emails']['administration']))
                                    ->schema([
                                        TableRepeater::make('emails.administration')
                                            ->headers(['Email'])
                                            ->label(false)
                                            ->createItemButtonLabel(__('filament-admin.settings.pages.company.repeater.email'))
                                            ->schema([
                                                TextInput::make('mail')
                                                    ->label(false)
                                                    ->email(fn ($state) => !empty($state)),
                                            ]),
                                    ]),
                                Tab::make('Transactional')
                                    ->badge(fn ($state) => count($state['emails']['transactional']))
                                    ->schema([
                                        TableRepeater::make('emails.transactional')
                                            ->headers(['Email'])
                                            ->label(false)
                                            ->createItemButtonLabel(__('filament-admin.settings.pages.company.repeater.email'))
                                            ->schema([
                                                TextInput::make('mail')
                                                    ->label(false)
                                                    ->email(fn ($state) => !empty($state)),
                                            ]),
                                    ]),
                            ]),
                        ]),
                    Tab::make(__('filament-admin.settings.pages.company.tabs.events_emails'))
                        ->icon('heroicon-o-inbox-in')
                        ->schema([
                            Placeholder::make('Events emails associations')
                                ->label(false)
                                ->dehydrated(false)
                                ->content(new HtmlString('
                                    <h3 class="text-xl font-bold mb-2">' . __('filament-admin.settings.pages.company.repeater.events_emails.placeholder.title') . '</h3>
                                    <p>' . __('filament-admin.settings.pages.company.repeater.events_emails.placeholder.content') . '</p>
                                ')),
                            TableRepeater::make('events_emails')
                                ->headers([
                                    'Event',
                                    'Email Group',
                                    'Email',
                                ])
                                ->label(false)
                                ->createItemButtonLabel(__('filament-admin.settings.pages.company.repeater.events_emails.add_button'))
                                ->schema([
                                    Select::make('event')
                                        ->label(false)
                                        ->options(function () {
                                            return Arr::prepend(
                                                MailHelper::make()->getEventsThatFireEmails(),
                                                __('filament-admin.settings.pages.company.repeater.events_emails.select_all'),
                                                'all'
                                            );
                                        }),
                                    Select::make('email_group')
                                        ->label(false)
                                        ->reactive()
                                        ->options(function () {
                                            return array_combine(
                                                array_keys(CompanySettings::make()->emails),
                                                array_keys(CompanySettings::make()->emails),
                                            );
                                        }),
                                    Select::make('email')
                                        ->label(false)
                                        ->options(function (callable $get) {
                                            $emailsForSelectedGroup = collect(CompanySettings::make()->emails)
                                                ->get($get('email_group'));

                                            return collect($emailsForSelectedGroup)
                                                ->mapWithKeys(fn ($item) => [$item['mail'] => $item['mail']])
                                                ->prepend(
                                                    __('filament-admin.settings.pages.company.repeater.events_emails.select_all'),
                                                    'all'
                                                )
                                                ->toArray();
                                        }),
                                ]),
                        ]),
                    Tab::make(__('filament-admin.settings.pages.company.tabs.social'))
                        ->icon('heroicon-o-link')
                        ->schema([
                            Grid::make()->schema([
                                TextInput::make('social.facebook')->url(),
                                TextInput::make('social.instagram')->url(),
                                TextInput::make('social.twitter')->url(),
                                TextInput::make('social.linkedin')->url(),
                                TextInput::make('social.youtube')->url(),
                                TextInput::make('social.tiktok')->url(),
                            ]),
                        ]),
                    Tab::make(__('filament-admin.settings.pages.company.tabs.analitycs'))
                        ->icon('heroicon-o-chart-bar')
                        ->schema([
                            Fieldset::make('Facebook')->schema([
                                TextInput::make('analitycs.facebook')
                                    ->label('Pixel ID')
                                    ->columnSpan(2),
                            ]),
                            Fieldset::make('Google')->schema([
                                TextInput::make('analitycs.google.key')->columnSpan(2),
                                Textarea::make('analitycs.google.snippet'),
                            ]),
                        ]),
                    Tab::make(__('filament-admin.settings.pages.company.tabs.color'))
                        ->icon('heroicon-o-color-swatch')
                        ->schema([
                            Grid::make(3)->schema([
                                ColorPicker::make('colors.primary'),
                                ColorPicker::make('colors.secondary'),
                                ColorPicker::make('colors.accent'),
                            ]),
                        ]),
                    Tab::make(__('filament-admin.settings.pages.company.tabs.positioning'))
                        ->icon('heroicon-o-globe')
                        ->schema([
                            Textarea::make('vision'),
                            Textarea::make('mission'),
                        ]),
                    Tab::make(__('filament-admin.settings.pages.company.tabs.text'))
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Repeater::make('text')
                                ->label(false)
                                ->createItemButtonLabel(__('filament-admin.settings.pages.company.repeater.text'))
                                ->schema([
                                    TextInput::make('title'),
                                    RichEditor::make('value')->label(false),
                                ]),
                        ]),
                ]),
        ];
    }

    protected function afterSave(): void
    {
        redirect(request()->header('Referer'));
    }
}
