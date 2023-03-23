<?php

namespace App\Settings;

use App\Traits\HasMakeConstructor;
use Illuminate\Support\Arr;
use Spatie\LaravelSettings\Settings;

class CompanySettings extends Settings
{
    use HasMakeConstructor;

    public string $name;

    public bool $active;

    public array $colors;

    public array $social;

    public array $analitycs;

    public ?string $address;

    public ?string $city;

    public ?string $state;

    public ?string $country;

    public ?string $phone;

    public array $emails;

    public array $events_emails;

    public ?string $vision;

    public ?string $mission;

    public array $text;

    public static function group(): string
    {
        return 'company';
    }

    public function getEmailsInsideGroup(string $group): array
    {
        return Arr::pluck(
            $this->emails[$group],
            'mail'
        );
    }

    public function getEmailsInsideGroups(array $groups): array
    {
        $emails = [];

        foreach ($groups as $key => $group) {
            $emails[] = $this->getEmailsInsideGroup($group);
        }

        return Arr::flatten($emails);
    }

    public function getAllRecipientsForEvent(string $event): array
    {
        $eventsEmailsFilteredByEvent = collect($this->events_emails)
            ->where('event', $event);

        $emailsInsideGroups = $this->getEmailsInsideGroups(
            $eventsEmailsFilteredByEvent
                ->where('email', 'all')
                ->pluck('email_group')
                ->toArray()
        );

        return array_unique(
            array_merge(
                $emailsInsideGroups,
                $eventsEmailsFilteredByEvent
                    ->whereNotBetween('email', ['all'])
                    ->pluck('email')
                    ->toArray()
            )
        );
    }

    public function getRecipientsForAllEvents(): array
    {
        return $this->getAllRecipientsForEvent('all');
    }
}
