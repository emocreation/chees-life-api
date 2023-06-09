<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Role::firstOrCreate(['name' => 'Super Admin']);

        $actions = ['view', 'create', 'update', 'delete'];
        $sections = [
            'user', 'district', 'social_media', 'category', 'review', 'service', 'customer', 'customer_history', 'banner', 'role', 'timeslot'
        ];

        foreach ($sections as $section) {
            foreach ($actions as $action) {
                $name = $action . '#' . $section;
                Permission::firstOrCreate(['name' => $name]);
            }
        }
        $permissions = Permission::all();
        Role::where('name', 'Super Admin')->first()->syncPermissions($permissions);
    }
}
