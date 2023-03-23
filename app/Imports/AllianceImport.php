<?php

namespace App\Imports;

use App\Models\Alliance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class AllianceImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $key => $row) {
                Alliance::create([
                    'alliance_id' => $row['alliance_id'],
                    'family_friendly' => $row['family_friendly'],
                    'date' => $row['date'],
                ]);
            }
        });
    }
}
