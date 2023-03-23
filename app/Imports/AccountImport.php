<?php

namespace App\Imports;

use App\Models\Account;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class AccountImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $key => $row) {
                Account::create([
                    'account_id' => $row['account_id'],
                    'alliance_id' => $row['alliance_id'],
                    'session_count' => $row['session_count'],
                    'session_duration' => $row['session_duration'],
                    'transaction_count' => $row['transaction_count'],
                    'revenue' => $row['revenue'],
                    'date' => $row['date'],
                    'active' => $row['active'],
                    'account_state' => $row['account_state'],
                    'last_active_date' => $row['last_active_date'],
                    'level' => $row['level'],
                    'created_language' => $row['created_language'],
                    'created_country_code' => $row['created_country_code'],
                    'created_time' => $row['created_time'],
                    'session_count_today' => $row['session_count_today'],
                    'session_duration_today' => $row['session_duration_today'],
                    'transaction_count_today' => $row['transaction_count_today'],
                    'revenue_today' => $row['revenue_today'],
                    'last_login_game_client_language' => $row['last_login_game_client_language'],
                    'last_login_country_code' => $row['last_login_country_code'],
                ]);
            }
        });
    }
}
