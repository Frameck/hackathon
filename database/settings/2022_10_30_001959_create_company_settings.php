<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class CreateCompanySettings extends SettingsMigration
{
    public function up(): void
    {
        $colors = [
            'primary' => null,
            'secondary' => null,
            'accent' => null,
        ];
        $social = [
            'facebook' => null,
            'instagram' => null,
            'twitter' => null,
            'linkedin' => null,
            'youtube' => null,
            'tiktok' => null,
        ];
        $analitycs = [
            'facebook' => [],
            'google' => [],
        ];
        $emails = [
            'info' => [],
            'customer_care' => [],
            'administration' => [],
            'transactional' => [],
        ];

        $this->migrator->add('company.name', config('app.name'));
        $this->migrator->add('company.active', true);
        $this->migrator->add('company.colors', $colors);
        $this->migrator->add('company.social', $social);
        $this->migrator->add('company.analitycs', $analitycs);
        $this->migrator->add('company.address', null);
        $this->migrator->add('company.city', null);
        $this->migrator->add('company.state', null);
        $this->migrator->add('company.country', null);
        $this->migrator->add('company.phone', null);
        $this->migrator->add('company.emails', $emails);
        $this->migrator->add('company.events_emails', []);
        $this->migrator->add('company.vision', null);
        $this->migrator->add('company.mission', null);
        $this->migrator->add('company.text', []);
    }
}
