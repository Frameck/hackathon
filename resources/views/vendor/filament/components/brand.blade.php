<div class="filament-brand text-xl font-bold tracking-tight dark:text-white flex items-center gap-3">
    @if (config('filament-admin.layout.sidebar.should_show_logo'))
        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Laravel.svg" alt="{{ str(config('app.name') . ' logo')->slug() }}" class="h-10">
    @endif
    {{ config('app.name') }}
</div>