<?php

namespace App\Imports;

use App\Models\Alliance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AllianceImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithChunkReading, ShouldQueue
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

    public function chunkSize(): int
    {
        return 1000;
    }
}
