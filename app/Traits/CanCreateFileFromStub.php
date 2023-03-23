<?php

namespace App\Traits;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Stringable;

trait CanCreateFileFromStub
{
    protected ?Stringable $type = null;

    protected string $namespace = '';

    protected string $stubName = '';

    protected string $stubPath = '';

    protected array $stubVariables = [];

    protected string $className = '';

    protected string $pathWhereToPlaceNewFile = '';

    protected Filesystem $fileSystem;

    public function createClassFromStub(): void
    {
        $this->makeDirectory();

        $contents = $this->getStubContents();

        if (!$this->fileSystem->exists($this->pathWhereToPlaceNewFile) || $this->option('force')) {
            $this->fileSystem->put($this->pathWhereToPlaceNewFile, $contents);
            $this->info(str($this->namespace)->singular() . " [app/{$this->namespace}/{$this->className}.php] created successfully");
        } else {
            $this->error(str($this->namespace)->singular() . " [app/{$this->namespace}/{$this->className}.php] already exits");
        }
    }

    public function setUp(Filesystem $fileSystem): void
    {
        $this->fileSystem($fileSystem);
        $this->setNamespace();
        $this->setStubName();
        $this->className();
        $this->stubPath();
        $this->pathWhereToPlaceNewFile();
        $this->mappedStubVariables();
    }

    protected function fileSystem(Filesystem $fileSystem): void
    {
        $this->fileSystem = $fileSystem;
    }

    public function setNamespace(): void
    {
        $this->namespace = $this->type->plural()->ucfirst()->toString();
    }

    public function setStubName(): void
    {
        $this->stubName = $this->type->toString();
    }

    protected function stubPath(): void
    {
        $this->stubPath = base_path('stubs/custom/' . $this->stubName . '.stub');
    }

    protected function mappedStubVariables(): void
    {
        $this->stubVariables['namespace'] = 'App\\' . $this->namespace;
        $this->stubVariables['class'] = $this->className;
    }

    protected function getStubContents(): mixed
    {
        $contents = $this->fileSystem->get($this->stubPath);

        foreach ($this->stubVariables as $search => $replace) {
            $contents = str_replace('{{ ' . $search . ' }}', $replace, $contents);
        }

        return $contents;
    }

    protected function pathWhereToPlaceNewFile(): void
    {
        $this->pathWhereToPlaceNewFile = app_path('/' . $this->namespace . '/') . $this->className . '.php';
    }

    protected function className(): void
    {
        $this->className = ucwords($this->argument('name'));
    }

    protected function makeDirectory(): void
    {
        $directoryPath = dirname($this->pathWhereToPlaceNewFile);

        if (!$this->fileSystem->isDirectory($directoryPath)) {
            $this->fileSystem->makeDirectory($directoryPath, 0777, true, true);
        }
    }
}
