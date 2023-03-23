<?php

namespace App\Console\Commands;

use App\Traits\CanCreateFileFromStub;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeInterfaceCommand extends Command
{
    use CanCreateFileFromStub;

    protected $signature = 'make:interface 
        {name}: Name of the interface class 
        {--f|force}: If present override the existing file';

    protected $description = 'Make an interface class';

    public function __construct()
    {
        parent::__construct();

        $this->setType();
    }

    public function setType(): void
    {
        $this->type = str('interface');
    }

    public function setNamespace(): void
    {
        $this->namespace = 'Contracts';
    }

    public function handle(Filesystem $files)
    {
        $this->setUp($files);
        $this->createClassFromStub();
    }
}
