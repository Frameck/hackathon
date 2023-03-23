<?php

namespace App\Console\Commands;

use App\Traits\CanCreateFileFromStub;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeEnumCommand extends Command
{
    use CanCreateFileFromStub;

    protected $signature = 'make:enum 
        {name}: Name of the enum class 
        {--f|force}: If present override the existing file';

    protected $description = 'Make an enum class';

    public function __construct()
    {
        parent::__construct();

        $this->setType();
    }

    public function setType(): void
    {
        $this->type = str('enum');
    }

    public function handle(Filesystem $files)
    {
        $this->setUp($files);
        $this->createClassFromStub();
    }
}
