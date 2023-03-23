<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $environment = app()->environment();

        foreach (config('users.' . $environment) as $key => $user) {
            if (!$user['email'] && !$user['password']) {
                continue;
            }

            User::create($user);
        }
    }
}
