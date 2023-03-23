<?php

namespace App\Imports;

use App\Models\ChatMessage;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ChatMessageImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithChunkReading, ShouldQueue
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $key => $row) {
                $time = str($row['timestamp'])->beforeLast('.')->append('Z')->toString();
                $time = Carbon::parse($time)->setTimezone('UTC');

                $filteredContent = str($row['filtered_content'])
                    ->remove('[')
                    ->remove(']')
                    ->remove('\'')
                    ->split('/\ /')
                    ->toArray();

                ChatMessage::create([
                    'account_id' => $row['account_id'],
                    'alliance_id' => $row['alliance_id'],
                    'timestamp' => $time,
                    'date' => $row['date'],
                    'raw_message' => $row['raw_message'],
                    'filtered_message' => $row['filtered_message'],
                    'filtered' => $row['filtered'],
                    'filtered_content' => $filteredContent,
                    'risk' => $row['risk'],
                    'filter_detected_language' => $row['filter_detected_language'],
                    'is_family_friendly' => $row['is_family_friendly'],
                    'general_risk' => $row['general_risk'],
                    'bullying' => $row['bullying'],
                    'violence' => $row['violence'],
                    'relationship_sexual_content' => $row['relationship_sexual_content'],
                    'vulgarity' => $row['vulgarity'],
                    'drugs_alcohol' => $row['drugs_alcohol'],
                    'in_app' => $row['in_app'],
                    'alarm' => $row['alarm'],
                    'fraud' => $row['fraud'],
                    'hate_speech' => $row['hate_speech'],
                    'religious' => $row['religious'],
                    'website' => $row['website'],
                    'child_grooming' => $row['child_grooming'],
                    'public_threat' => $row['public_threat'],
                    'extremism' => $row['extremism'],
                    'subversive' => $row['subversive'],
                    'sentiment' => $row['sentiment'],
                    'politics' => $row['politics'],
                ]);
            }
        });
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
