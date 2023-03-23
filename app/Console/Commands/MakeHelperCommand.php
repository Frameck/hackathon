<?php

namespace App\Console\Commands;

use App\Traits\CanCreateFileFromStub;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeHelperCommand extends Command
{
    use CanCreateFileFromStub;

    protected $signature = 'make:helper 
        {name}: Name of the helper class 
        {--f|force}: If present override the existing file';

    protected $description = 'Make an helper class (class that contains helpful methods and functions)';

    public function __construct()
    {
        parent::__construct();

        $this->setType();
    }

    public function setType(): void
    {
        $this->type = str('helper');
    }

    public function handle(Filesystem $files)
    {
        $this->setUp($files);
        $this->createClassFromStub();
    }
}
