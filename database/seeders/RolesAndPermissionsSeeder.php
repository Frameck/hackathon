<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        foreach (config('roles') as $key => $value) {
            foreach ($value as $key2 => $value2) {
                Role::create($value2);
            }
        }
    }
}
