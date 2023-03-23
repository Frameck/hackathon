<?php

namespace App\Console\Commands;

use App\Traits\CanCreateFileFromStub;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeActionCommand extends Command
{
    use CanCreateFileFromStub;

    protected $signature = 'make:action 
        {name}: Name of the action class 
        {--f|force}: If present override the existing file';

    protected $description = 'Make an action class';

    public function __construct()
    {
        parent::__construct();

        $this->setType();
    }

    public function setType(): void
    {
        $this->type = str('action');
    }

    public function handle(Filesystem $files)
    {
        $this->setUp($files);
        $this->createClassFromStub();
    }
}
