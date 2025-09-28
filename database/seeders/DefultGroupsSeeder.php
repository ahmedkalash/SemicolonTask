<?php

namespace Database\Seeders;

use App\Models\group;
use App\Models\GroupPermission;
use App\Models\Permission;
use Illuminate\Database\Seeder;

// seeding defult groups
class DefultGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // defult groups
        $groups = [
            ['name' => 'read only'],
            ['name' => 'read & write'],
            ['name' => 'full access'],
        ];
        $columnsToBeUpdated = ['name'];
        Group::upsert($groups, ['name'], $columnsToBeUpdated);

        // attaching permissions to defult grops 
        $permissions = Permission::all()->pluck('id', 'name')->toArray();
        $groups = group::all()->pluck('id', 'name')->toArray();
        $groups_permissions = [
            $groups['read only'] => [
                $permissions['view_users'],
                $permissions['view_groups'],
            ],
            $groups["read & write"] => [
                $permissions['view_users'],
                $permissions['create_users'],
                $permissions['update_users'],

                $permissions['view_groups'],
                $permissions['create_groups'],
                $permissions['update_groups'],

            ],
            $groups["full access"] => Permission::get("id")
                ->map(fn($item) => $item->id)
                ->toArray(),
        ];
        foreach ($groups_permissions as $groupId => $permissionIds) {
            /**
             * Inner loop iterates over the array of Permission IDs for the current group.
             * The final output will be like:
             * $groups_permissions_new = [
             *      ['group_id'=> $groups['read only'], 'permission_id'=> $permissions['view_users']],
             *      ['group_id'=> $groups["read & write"], 'permission_id'=> $permissions['view_users']],
             *      ['group_id'=> $groups["read & write"], 'permission_id'=> $permissions['create_users']],
             *      ['group_id'=> $groups["read & write"], 'permission_id'=> $permissions["update_users"]],
             * ];
             */
            foreach ($permissionIds as $permissionId) {
                // Construct the required associative array for the pivot table
                $groups_permissions_new[] = [
                    'group_id' => $groupId,
                    'permission_id' => $permissionId,
                ];
            }
        }

        $columnsToBeUpdated = ['group_id', 'permission_id'];
        GroupPermission::upsert($groups_permissions_new, [''], $columnsToBeUpdated);

    }
}
