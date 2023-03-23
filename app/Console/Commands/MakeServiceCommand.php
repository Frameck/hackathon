<?php

namespace App\Console\Commands;

use App\Helpers\AppHelper;
use App\Helpers\DatabaseHelper;
use App\Traits\CanCreateFileFromStub;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Stringable;

class MakeServiceCommand extends Command
{
    use CanCreateFileFromStub;

    protected $signature = 'make:service 
        {name}: Name of the service class 
        {--M|model}: Indicate if the service should be tied to a model 
        {--validation}: Indicate if the service should include validation rules 
        {--f|force}: If present override the existing file';

    protected $description = 'Make a service class';

    public function __construct()
    {
        parent::__construct();

        $this->setType();
    }

    public function setType(): void
    {
        $this->type = str('service');
    }

    public function handle(Filesystem $files)
    {
        $this->setUp($files);
        $this->setStubPath();
        $this->setMappedStubVariables();
        $this->createClassFromStub();
        shell_exec('./vendor/bin/pint');
    }

    // protected function className(): void
    // {
    //     $typeSuffix = $this->type->ucfirst()->toString();
    //     $nameContainsTypeSuffix = str_contains($this->argument('name'), $typeSuffix);

    //     $this->className = str($this->argument('name'))
    //         ->when(
    //             !$nameContainsTypeSuffix,
    //             fn (Stringable $string) => $string->append($typeSuffix)
    //         )
    //         ->camel()
    //         ->ucfirst()
    //         ->toString();
    // }

    public function setStubPath(): void
    {
        if ($this->option('model')) {
            $this->stubPath = str($this->stubPath)->beforeLast('.stub') . '.model.stub';
        }

        if ($this->option('validation')) {
            $this->stubPath = str($this->stubPath)->beforeLast('.stub') . '.validation.stub';
        }
    }

    public function setMappedStubVariables(): void
    {
        $additionalStubVariables = [];

        $modelName = str($this->argument('name'))
            ->beforeLast($this->type->ucfirst()->toString())
            ->camel()
            ->ucfirst()
            ->toString();

        if ($this->option('model')) {
            $additionalStubVariables['model'] = $modelName;
        }

        if ($this->option('validation')) {
            $databaseHelper = DatabaseHelper::make("App\Models\\{$modelName}");
            $validationRulesAsJson = json_encode(
                $databaseHelper->getValidationRules(),
                JSON_PRETTY_PRINT
            );

            $additionalStubVariables['validationRules'] = AppHelper::tranformJsonToPhp($validationRulesAsJson);
        }

        $this->stubVariables = array_merge(
            $this->stubVariables,
            $additionalStubVariables
        );
    }
}
