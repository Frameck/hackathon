<?php

namespace App\Helpers;

use App\Settings\CompanySettings;
use App\Traits\HasMakeConstructor;

class MailHelper
{
    use HasMakeConstructor;

    public function getEventsThatFireEmails(): array
    {
        return [
            // list of events that fire an email
            // 'order-created' => 'Ordine creato',
            // 'order-shipped' => 'Ordine spedito',
        ];
    }

    public function getDefaultRecipientsForEvent(string $event): array
    {
        $companySettings = CompanySettings::make();

        return array_unique(
            array_merge(
                $companySettings->getAllRecipientsForEvent($event),
                $companySettings->getRecipientsForAllEvents()
            )
        );
    }
}
