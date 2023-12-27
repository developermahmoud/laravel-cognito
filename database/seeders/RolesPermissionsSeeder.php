<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    protected array $clientPermissions = [
        'create rfp',
        'list rfp',
        'delete rfp',
        'update rfp',
    ];

    protected array $vendorPermissions = [
        'submit proposal',
        'list rfps',
        'approve rfp',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Client role
         */
        $role = Role::firstOrCreate(['name' => 'client']);
        foreach ($this->clientPermissions as $item) {
            $permission = Permission::firstOrCreate([
                'name' => $item,
            ]);
            $role->givePermissionTo($permission);
        }

        /**
         * Vendor role
         */
        $role = Role::firstOrCreate(['name' => 'client']);
        foreach ($this->vendorPermissions as $item) {
            $permission = Permission::firstOrCreate([
                'name' => $item,
            ]);
            $role->givePermissionTo($permission);
        }
    }
}
