<?php

namespace App\Imports;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccountImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithChunkReading, ShouldQueue
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $key => $row) {
                $time = str($row['created_time'])->beforeLast('.')->toString();
                $time = Carbon::createFromFormat('Y-m-d H:i:s', $time);

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
                    'created_time' => $time,
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

    public function chunkSize(): int
    {
        return 1000;
    }
}
