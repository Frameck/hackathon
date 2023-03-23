<x-filament::widget>
    <x-filament::card>
        <div class="relative h-12 flex flex-col justify-center items-center space-y-2">
            <a 
                href="{{ route('homepage') }}"
                target="_blank"
                @class([
                    'space-y-1 flex items-end space-x-2 rtl:space-x-reverse text-gray-800 hover:text-primary-500 transition cursor-pointer',
                    'dark:text-primary-500 dark:hover:text-primary-400' => config('filament.dark_mode'),
                ])
            >
                <h2 class="text-lg sm:text-xl font-bold tracking-tight">
                    {{ config('app.name') }}
                </h2>
            </a>

            <div class="text-sm flex space-x-2 rtl:space-x-reverse">
                <a
                    href="{{ route('homepage') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    @class([
                        'text-gray-600 hover:text-primary-500 focus:outline-none focus:underline',
                        'dark:text-gray-300 dark:hover:text-primary-500' => config('filament.dark_mode'),
                    ])
                >
                    {{ __('filament-admin.widgets.dashboard.brand.visit_site') }}
                </a>

                <span>
                    &bull;
                </span>

                <a
                    href="{{ route('filament.pages.company-settings') }}"
                    target="_self"
                    rel="noopener noreferrer"
                    @class([
                        'text-gray-600 hover:text-primary-500 focus:outline-none focus:underline',
                        'dark:text-gray-300 dark:hover:text-primary-500' => config('filament.dark_mode'),
                    ])
                >
                    {{ __('filament-admin.widgets.dashboard.brand.company_settings') }}
                </a>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>
