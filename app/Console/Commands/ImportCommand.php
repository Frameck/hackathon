<?php

namespace App\Console\Commands;

use App\Imports\ChatMessageImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class ImportCommand extends Command
{
    protected $signature = 'import:run';

    protected $description = 'Import data from csv';

    public function handle()
    {
        FacadesExcel::import(new ChatMessageImport, storage_path('/app/public/import/chat_messages_2.csv'));
    }
}
