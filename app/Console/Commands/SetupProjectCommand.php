<?php

namespace App\Console\Commands;

use App\Helpers\AppHelper;
use Carbon\CarbonInterval;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class SetupProjectCommand extends Command
{
    protected $signature = 'project:setup
        {--filament}: Generate Filament resources for admin panel 
        {--service}: Generate Service classes to handle business logic 
        {--api}: Generate api resource controllers with form requests
        {--resource}: Generate JSON resources to be used inside api controllers';

    protected $description = '
        Command that bootstraps the entire project base complete with Filament resources, Service classes with validation rules, Api Controllers, JSON resources and form requests.
        To use this command, it is necessary that all models be set as well as all the migrations because it reades directly from the database tables.
        It\'s also important that all models have the proper relationships set up with the correct return type.';

    public function handle()
    {
        $this->warn($this->getDescription());

        if (!$this->confirm('Do you want to continue?')) {
            return;
        }

        $generateFilament = $this->option('filament');
        $generateService = $this->option('service');
        $generateApi = $this->option('api');
        $generateResource = $this->option('resource');

        $startTime = now();

        $models = $this->getModelsToIterate();
        $skippedModels = [];

        $bar = $this->output->createProgressBar(count($models));
        $bar->start();

        foreach ($models as $model) {
            if ($this->modelIsPivot($model) || $model == 'User') {
                $skippedModels[] = $model;
                $bar->advance();

                continue;
            }

            if ($generateFilament) {
                Artisan::call('make:filament-resource', [
                    'name' => $model . 'Resource',
                    '--generate' => true,
                    '--soft-deletes' => true,
                ]);
            }

            if ($generateService) {
                Artisan::call('make:service', [
                    'name' => $model . 'Service',
                    '--validation' => true,
                ]);
            }

            if ($generateApi) {
                Artisan::call('make:controller', [
                    'name' => 'Api/' . $model . 'Controller',
                    '--model' => $model,
                    '--requests' => true,
                    '--api' => true,
                    '-r' => true,
                ]);

                Artisan::call('make:request', [
                    'name' => 'Index' . $model . 'Request',
                ]);
            }

            if ($generateResource) {
                Artisan::call('make:resource', [
                    'name' => $model . 'Resource',
                ]);
            }

            $bar->advance();
        }

        $this->applyIndexRequestToControllers();

        shell_exec('./vendor/bin/pint');

        $bar->finish();
        $this->newLine(2);

        $endTime = $startTime->diffInSeconds(now());
        $generatedModelsCount = count($models) - count($skippedModels);
        $totalGeneratedClasses = $this->calculateTotalGeneratedClasses($generatedModelsCount, [
            'filament' => $generateFilament,
            'service' => $generateService,
            'api' => $generateApi,
            'resource' => $generateResource,
        ]);

        if (!empty($skippedModels)) {
            $this->warn('Skipped ' . count($skippedModels) . ' models [' . implode(', ', $skippedModels) . ']');
        }

        $this->info('Generated ' . $totalGeneratedClasses . ' classes for ' . $generatedModelsCount . ' models in ' . CarbonInterval::seconds($endTime)->cascade()->forHumans());

        return Command::SUCCESS;
    }

    public function getModelsToIterate(): array
    {
        return AppHelper::getModelsNames();
    }

    public function modelIsPivot(string $model): bool
    {
        return is_subclass_of("App\Models\\{$model}", Pivot::class);
    }

    public function calculateTotalGeneratedClasses(int $modelsCount, array $options): int
    {
        $total = 0;

        if ($options['filament']) {
            $total += $modelsCount * 4;
        }

        if ($options['service']) {
            $total += $modelsCount;
        }

        if ($options['api']) {
            $total += $modelsCount * 4;
        }

        if ($options['resource']) {
            $total += $modelsCount;
        }

        return $total;
    }

    public function applyIndexRequestToControllers(): bool
    {
        $fileSystem = new Filesystem;
        $controllers = $fileSystem->files(
            app_path('Http/Controllers/Api')
        );

        foreach ($controllers as $controller) {
            if ($controller->isDir()) {
                continue;
            }

            $controller = str($controller->getFilename());

            $modelName = str($controller)
                ->before('Controller.php')
                ->toString();

            $controllerPath = app_path('Http/Controllers/Api/' . $controller);

            $contents = $fileSystem->get($controllerPath);

            $newContents = str($contents)
                ->replace(
                    ['Illuminate\Http\Request', 'index(Request'],
                    ["App\Http\Requests\Index{$modelName}Request", "index(Index{$modelName}Request"]
                );

            $fileSystem->put($controllerPath, $newContents);
        }

        return true;
    }
}
