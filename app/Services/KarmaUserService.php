<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Traits\HasMakeConstructor;

class KarmaUserService
{
    use HasMakeConstructor;

    public $user_id;

    public $sub_karmas;

    public $gamma_accumulated;

    public $general_karma;

    public $last_updated;

    public static function make($user_id): static
    {
        return app(static::class, [
            'user_id' => $user_id,
        ]);
    }

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->sub_karmas = [
            'bullying' => 0,
            'violence' => 0,
            'relationship_sexual_content' => 0,
            'vulgarity' => 0,
            // 'drugs_alcohol' => 0,
            // 'in_app' => 0,
            // 'alarm' => 0,
            'fraud' => 0,
            'hate_speech' => 0,
            // 'religious' => 0,
            // 'website' => 0,
            'child_grooming' => 0,
            'public_threat' => 0,
            // 'extremism' => 0,
            // 'subversive' => 0,
            // 'sentiment' => 0,
            // 'politics' => 0,
        ];
        $this->gamma_accumulated = [
            'bullying' => 0,
            'violence' => 0,
            'relationship_sexual_content' => 0,
            'vulgarity' => 0,
            // 'drugs_alcohol' => 0,
            // 'in_app' => 0,
            // 'alarm' => 0,
            'fraud' => 0,
            'hate_speech' => 0,
            // 'religious' => 0,
            // 'website' => 0,
            'child_grooming' => 0,
            'public_threat' => 0,
            // 'extremism' => 0,
            // 'subversive' => 0,
            // 'sentiment' => 0,
            // 'politics' => 0,
        ];
        $this->general_karma = 0;
        $this->last_updated = now()->getTimestamp();
    }

    public function update_sub_karma($sub_karma_name, $offense_level, $down = true)
    {
        $alpha_gamma_values = [
            'bullying' => [0.5, 0.7],
            'violence' => [1.0, 1.0],
            'relationship_sexual_content' => [0.7, 0.8],
            'vulgarity' => [0.2, 0.5],
            // 'drugs_alcohol' => [0.3, 1.0],
            // 'in_app' => [0.3, 0.7],
            // 'alarm' => [0.5, 0.5],
            'fraud' => [0.6, 0.8],
            'hate_speech' => [0.4, 0.7],
            // 'religious' => [0.5, 0.5],
            // 'website' => [0.3, 0.5],
            'child_grooming' => [1.0, 1.0],
            'public_threat' => [0.7, 0.9],
            // 'extremism' => [0.6, 0.9],
            // 'subversive' => [0.3, 0.7],
            // 'sentiment' => [0.5, 0.5],
            // 'politics' => [0.3, 0.5],
        ];
        $decay_values = [
            'bullying' => 0.005,
            'violence' => 0.003,
            'relationship_sexual_content' => 0.002,
            'vulgarity' => 0.006,
            // 'drugs_alcohol' => 0.008,
            // 'in_app' => 0.002,
            // 'alarm' => 0.005,
            'fraud' => 0.005,
            'hate_speech' => 0.002,
            // 'religious' => 0.009,
            // 'website' => 0.009,
            'child_grooming' => 0.001,
            'public_threat' => 0.005,
            // 'extremism' => 0.0004,
            // 'subversive' => 0.0006,
            // 'sentiment' => 0.008,
            // 'politics' => 0.008,
        ];

        [$alpha, $gamma] = $alpha_gamma_values[$sub_karma_name];
        $decay = $decay_values[$sub_karma_name];

        $current_time = now()->getTimestamp();
        $time_passed = $current_time - $this->last_updated;
        $this->last_updated = $current_time;

        // Apply decay to sub-karma and gamma accumulated
        $decay_factor = 1 - (1 / (1 + $time_passed * $decay));

        if (!$down) {
            $decay_factor = 1 + (1 / (1 + $time_passed * $decay));
        }

        $this->sub_karmas[$sub_karma_name] = max($this->sub_karmas[$sub_karma_name] * $decay_factor, 0);
        $this->gamma_accumulated[$sub_karma_name] = max($this->gamma_accumulated[$sub_karma_name] * $decay_factor, 0);

        // Update sub-karma based on offense level (0-7)
        $this->sub_karmas[$sub_karma_name] += $alpha * $offense_level;
        $this->gamma_accumulated[$sub_karma_name] += $gamma * $offense_level;
        $this->sub_karmas[$sub_karma_name] += $this->gamma_accumulated[$sub_karma_name];

        // Update general karma
        $this->update_general_karma($sub_karma_name, $down);
    }

    public function simulate_time_passing($days_passed, $down = true)
    {
        $decay_values = [
            'bullying' => 0.000013,
            'violence' => 0.000011,
            'relationship_sexual_content' => 0.000010,
            'vulgarity' => 0.000014,
            // 'drugs_alcohol' => 0.000018,
            // 'in_app' => 0.000012,
            // 'alarm' => 0.000015,
            'fraud' => 0.000013,
            'hate_speech' => 0.000010,
            // 'religious' => 0.000019,
            // 'website' => 0.000019,
            'child_grooming' => 0.000009,
            'public_threat' => 0.000013,
            // 'extremism' => 0.0000104,
            // 'subversive' => 0.0000106,
            // 'sentiment' => 0.000018,
            // 'politics' => 0.000018,
        ];

        $seconds_passed = $days_passed * 24 * 60 * 60;

        foreach ($decay_values as $sub_karma_name => $decay) {
            $decay_factor = 1 - (1 / (1 + $seconds_passed * $decay));

            if (!$down) {
                $decay_factor = 1 + (1 / (1 + $seconds_passed * $decay));
            }

            $this->sub_karmas[$sub_karma_name] = max($this->sub_karmas[$sub_karma_name] * $decay_factor, 0);
            $this->gamma_accumulated[$sub_karma_name] = max($this->gamma_accumulated[$sub_karma_name] * $decay_factor, 0);
            $this->update_general_karma($sub_karma_name, $down);
        }
    }

    public function update_general_karma($sub_karma_name, $down = true)
    {
        $this->general_karma = array_sum($this->sub_karmas);

        // Apply the hard bottom cap based on the most severe offense
        $hard_cap = 0;
        if ($this->sub_karmas[$sub_karma_name] > 0) {
            $this->general_karma = max($this->general_karma, $hard_cap);
        }

        if ($down) {
            $this->general_karma = min(max($this->general_karma, 0), 100);  // Clamp general karma between 0 and 100
        } else {
            $this->general_karma = min(max($this->general_karma, 0), -100);  // Clamp general karma between 0 and 100
        }
    }

    public static function calculateKarma($user_id)
    {
        $userService = self::make($user_id);

        $messages = ChatMessage::query()
            ->with('alliance')
            ->where('account_id', $user_id)
            ->get();

        foreach ($messages as $key => $value) {
            $badStuff = [
                'bullying' => $value->bullying == -1 ? 0 : $value->bullying,
                'violence' => $value->violence == -1 ? 0 : $value->violence,
                'relationship_sexual_content' => $value->relationship_sexual_content == -1 ? 0 : $value->relationship_sexual_content,
                'vulgarity' => $value->vulgarity == -1 ? 0 : $value->vulgarity,
                // 'drugs_alcohol' => $value->drugs_alcohol == -1 ? 0 : $value->drugs_alcohol,
                // 'in_app' => $value->in_app == -1 ? 0 : $value->in_app,
                // 'alarm' => $value->alarm == -1 ? 0 : $value->alarm,
                'fraud' => $value->fraud == -1 ? 0 : $value->fraud,
                'hate_speech' => $value->hate_speech == -1 ? 0 : $value->hate_speech,
                // 'religious' => $value->religious == -1 ? 0 : $value->religious,
                // 'website' => $value->website == -1 ? 0 : $value->website,
                'child_grooming' => $value->child_grooming == -1 ? 0 : $value->child_grooming,
                'public_threat' => $value->public_threat == -1 ? 0 : $value->public_threat,
                // 'extremism' => $value->extremism == -1 ? 0 : $value->extremism,
                // 'subversive' => $value->subversive == -1 ? 0 : $value->subversive,
                // 'sentiment' => $value->sentiment == -1 ? 0 : $value->sentiment,
                // 'politics' => $value->politics == -1 ? 0 : $value->politics,
            ];

            foreach ($badStuff as $key2 => $value2) {
                // this violation has less gravity because it's in a non family friendly clan
                if ($value->alliance && $value->alliance->family_friendly == -1) {
                    if ($value2 <= 3) {
                        $userService->update_sub_karma($key2, $value2 * 0.7);

                        continue;
                    }
                }

                if ($value2 > 0) {
                    $userService->update_sub_karma($key2, $value2);
                }
            }

            if ($value->general_risk <= 0) {
                $userService->update_sub_karma($key2, -1);
            }
        }

        return $userService;
    }

    public static function calculateDecay($user_id)
    {
        $userService = self::calculateKarma($user_id);

        $decay = [
            [
                'user_id' => $user_id,
            ],
        ];

        for ($i = 0; $i < 12; $i++) {
            $decay[] = [
                'value' => $userService->general_karma,
                'sub_karmas' => $userService->sub_karmas,
            ];

            $userService->simulate_time_passing(15);
        }

        return $decay;
    }
}
