<?php

namespace App\Console\Commands;

use function App\Helpers\array_contains_only_numbers;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class AssignRoleCommand extends Command
{
    protected $signature = 'role:assign 
        {role}: Name of the role that should be assigned 
        {userIds?*}: One or multiple user ids to assign the role 
        {--A|all}: If present assigns the role to all users in database';

    protected $description = 'Assign a role to one or multiple users';

    public function handle()
    {
        $userIds = $this->argument('userIds');
        $assignToAllUsers = $this->option('all');

        if (!$this->hasArgument('userIds') && !$assignToAllUsers) {
            return $this->error('Provide at least one user id or --all to assign the role to all users in the database');
        }

        if (!array_contains_only_numbers($userIds)) {
            return $this->error('You must provide one or multiple user ids, not a string');
        }

        $role = Role::findByName($this->argument('role'));
        $users = User::query()
            ->when(
                $assignToAllUsers,
                fn (Builder $query) => $query->get(),
                fn (Builder $query) => $query->whereIn('id', $userIds)->get(),
            );

        foreach ($users as $key => $user) {
            if ($user->hasRole($role)) {
                $this->warn("Not assigning role {$role->name} to user {$user->email} because it's already assigned");

                continue;
            }

            $user->assignRole($role);
            $this->info("Assigned role {$role->name} to user {$user->email}");
        }
    }
}
