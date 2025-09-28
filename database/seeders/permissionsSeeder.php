<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seeding permissions
        $permissions = config('permissions');
        $columnsToBeUpdated = ['name'];
        Permission::upsert($permissions, ['name'], $columnsToBeUpdated);

    }
}
