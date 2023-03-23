<?php

namespace App\Providers;

use App\Helpers\BladeHelper;
use App\Helpers\CollectionHelper;
use App\Helpers\DatabaseHelper;
use App\Helpers\FilamentHelper;
use App\Helpers\QueryHelper;
use App\Helpers\ResponseHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();

        // Model::shouldBeStrict(app()->isLocal());
        // Model::preventLazyLoading(app()->isLocal());
        Model::preventSilentlyDiscardingAttributes(app()->isLocal());
        Model::preventAccessingMissingAttributes(app()->isLocal());

        DatabaseHelper::registerListener();
        BladeHelper::registerDirectives();
        FilamentHelper::registerConfigs();
        ResponseHelper::registerMacros();
        QueryHelper::registerMacros();
        CollectionHelper::registerMacros();
    }
}
