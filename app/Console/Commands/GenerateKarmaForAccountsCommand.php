<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Services\KarmaUserService;
use Illuminate\Console\Command;

class GenerateKarmaForAccountsCommand extends Command
{
    protected $signature = 'karma:generate';

    protected $description = 'Calculate karma for all accounts';

    public function handle()
    {
        Account::chunk(1000, function ($accounts) {
            foreach ($accounts as $key => $value) {
                $value->update([
                    'karma' => KarmaUserService::calculateKarma($value->account_id)->general_karma,
                ]);
            }
        });
    }
}
