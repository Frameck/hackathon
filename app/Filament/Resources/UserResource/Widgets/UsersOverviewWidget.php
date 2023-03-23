<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class UsersOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getCards(): array
    {
        return [
            Card::make('Active Users', User::withoutTrashed()->count()),
            Card::make('Inactive Users', User::onlyTrashed()->count()),
            Card::make('Total Users', User::withTrashed()->count())
                ->chart($this->getDataForTotalUsersChart()),
        ];
    }

    public function getDataForTotalUsersChart(): array
    {
        return User::withTrashed()
            ->orderBy('created_at')
            ->get()
            ->mapToGroups(function ($user) {
                return [
                    $user->created_at->format('Y') => $user->name,
                ];
            })
            ->map(fn ($usersGroupedByYear) => $usersGroupedByYear->count())
            ->toArray();
    }
}
