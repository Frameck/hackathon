<?php

namespace App\Imports;

use App\Models\ChatMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ChatMessageImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $key => $row) {
                ChatMessage::create([
                    'account_id' => $row['account_id'],
                    'alliance_id' => $row['alliance_id'],
                    'timestamp' => $row['timestamp'],
                    'date' => $row['date'],
                    'raw_message' => $row['raw_message'],
                    'filtered_message' => $row['filtered_message'],
                    'filtered' => $row['filtered'],
                    'filtered_content' => $row['filtered_content'],
                    'risk' => $row['risk'],
                    'filter_detected_language' => $row['filter_detected_language'],
                    'is_family_friendly' => $row['is_family_friendly'],
                    'general_risk' => $row['GENERAL_RISK'],
                    'bullying' => $row['BULLYING'],
                    'violence' => $row['VIOLENCE'],
                    'relationship_sexual_content' => $row['RELATIONSHIP_SEXUAL_CONTENT'],
                    'vulgarity' => $row['VULGARITY'],
                    'drugs_alcohol' => $row['DRUGS_ALCOHOL'],
                    'in_app' => $row['IN_APP'],
                    'alarm' => $row['ALARM'],
                    'fraud' => $row['FRAUD'],
                    'hate_speech' => $row['HATE_SPEECH'],
                    'religious' => $row['RELIGIOUS'],
                    'website' => $row['WEBSITE'],
                    'child_grooming' => $row['CHILD_GROOMING'],
                    'public_threat' => $row['PUBLIC_THREAT'],
                    'extremism' => $row['EXTREMISM'],
                    'subversive' => $row['SUBVERSIVE'],
                    'sentiment' => $row['SENTIMENT'],
                    'politics' => $row['POLITICS'],
                ]);
            }
        });
    }
}
