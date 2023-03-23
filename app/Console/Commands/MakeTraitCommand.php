<?php

namespace App\Console\Commands;

use App\Traits\CanCreateFileFromStub;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeTraitCommand extends Command
{
    use CanCreateFileFromStub;

    protected $signature = 'make:trait 
        {name}: Name of the trait class 
        {--f|force}: If present override the existing file';

    protected $description = 'Make a trait class';

    public function __construct()
    {
        parent::__construct();

        $this->setType();
    }

    public function setType(): void
    {
        $this->type = str('trait');
    }

    public function handle(Filesystem $files)
    {
        $this->setUp($files);
        $this->createClassFromStub();
    }
}
