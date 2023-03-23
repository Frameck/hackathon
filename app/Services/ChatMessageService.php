<?php

namespace App\Services;

use App\Contracts\CanProvideValidationRules;
use App\Models\ChatMessage;
use App\Traits\HasMakeConstructor;
use App\Traits\HasValidationRules;

class ChatMessageService implements CanProvideValidationRules
{
    use HasMakeConstructor;
    use HasValidationRules;

    protected static function indexValidationRules(): array
    {
        return static::baseIndexValidationRules();
    }

    public static function validationRules(): array
    {
        return [
            'account_id' => [
                'string',
                'max:255',
                'nullable',
                'exists:accounts,id',
            ],
            'alliance_id' => [
                'string',
                'max:255',
                'nullable',
                'exists:alliances,id',
            ],
            'timestamp' => [
                'date_format:Y-m-d H:i:s',
                'nullable',
            ],
            'date' => [
                'date_format:Y-m-d',
                'nullable',
            ],
            'raw_message' => [
                'string',
                'max:65535',
                'nullable',
            ],
            'filtered_message' => [
                'string',
                'max:65535',
                'nullable',
            ],
            'filtered' => [
                'boolean',
                'nullable',
            ],
            'filtered_content' => [
                'json',
                'nullable',
            ],
            'risk' => [
                'decimal:0',
                'nullable',
            ],
            'filter_detected_language' => [
                'string',
                'max:255',
                'nullable',
            ],
            'is_family_friendly' => [
                'boolean',
                'nullable',
            ],
            'general_risk' => [
                'integer',
                'numeric',
                'nullable',
            ],
            'bullying' => [
                'decimal:0',
                'nullable',
            ],
            'violence' => [
                'decimal:0',
                'nullable',
            ],
            'relationship_sexual_content' => [
                'decimal:0',
                'nullable',
            ],
            'vulgarity' => [
                'decimal:0',
                'nullable',
            ],
            'drugs_alcohol' => [
                'decimal:0',
                'nullable',
            ],
            'in_app' => [
                'decimal:0',
                'nullable',
            ],
            'alarm' => [
                'decimal:0',
                'nullable',
            ],
            'fraud' => [
                'decimal:0',
                'nullable',
            ],
            'hate_speech' => [
                'decimal:0',
                'nullable',
            ],
            'religious' => [
                'decimal:0',
                'nullable',
            ],
            'website' => [
                'decimal:0',
                'nullable',
            ],
            'child_grooming' => [
                'decimal:0',
                'nullable',
            ],
            'public_threat' => [
                'decimal:0',
                'nullable',
            ],
            'extremism' => [
                'decimal:0',
                'nullable',
            ],
            'subversive' => [
                'decimal:0',
                'nullable',
            ],
            'sentiment' => [
                'decimal:0',
                'nullable',
            ],
            'politics' => [
                'decimal:0',
                'nullable',
            ],
        ];
    }

    public static function getSurroundMessages(int $messageId): array
    {
        $message = ChatMessage::query()
            ->where('id', $messageId)
            ->with([
                'account',
                'alliance',
            ])
            ->first();

        $messages = ChatMessage::query()
            ->where('account_id', $message->account_id)
            ->where('alliance_id', $message->alliance_id)
            ->get();

        return [
            'message' => $message,
            'before' => $messages->where('timestamp', '<', $message->timestamp)->values(),
            'after' => $messages->where('timestamp', '>', $message->timestamp)->values(),
        ];
    }
}
