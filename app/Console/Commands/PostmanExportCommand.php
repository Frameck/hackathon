<?php

namespace App\Console\Commands;

use App\Helpers\PostmanHelper;
use Illuminate\Console\Command;

class PostmanExportCommand extends Command
{
    protected $signature = 'export:postman 
        {--path=}: Specify which path to export';

    protected $description = 'Exports a postman collection and environment file based on registered app api routes';

    public function handle(): int
    {
        $syncResult = PostmanHelper::export(
            $this->option('path') ?? 'api'
        );

        if (!$syncResult) {
            $this->error('Error while exporting files, please retry again.');

            return Command::FAILURE;
        }

        $this->info('Files exported successfully in storage/app/postman directory');
        shell_exec('open ' . storage_path('app/postman'));

        return Command::SUCCESS;
    }
}
