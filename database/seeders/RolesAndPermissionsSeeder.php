<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view admin panel',
            'manage properties',
            'manage agents',
            'manage reviews',
            'write reviews',
            'reply reviews',
            'create bookings',
            'manage bookings',
            'manage tickets',
            'open tickets',
            'upload kyc',
            'manage kyc',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        // Super admin gets all permissions (handled via Gate::before in AuthServiceProvider but we can assign them too)

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions([
            'view admin panel',
            'manage properties',
            'manage agents',
            'manage reviews',
            'manage bookings',
            'manage tickets',
            'manage kyc',
            'manage settings',
        ]);

        $contentManagerRole = Role::firstOrCreate(['name' => 'Content Manager']);
        $contentManagerRole->syncPermissions([
            'view admin panel',
            'manage properties',
            'manage reviews',
            'manage tickets',
        ]);

        $agentRole = Role::firstOrCreate(['name' => 'Agent']);
        $agentRole->syncPermissions([
            'manage properties',
            'reply reviews',
            'manage bookings',
            'open tickets',
            'upload kyc',
        ]);

        $customerRole = Role::firstOrCreate(['name' => 'Customer']);
        $customerRole->syncPermissions([
            'write reviews',
            'create bookings',
            'open tickets',
            'upload kyc',
        ]);

        // Map users
        $superAdminUser = User::where('email', 'admin@homelist.co.in')->first();
        if ($superAdminUser) {
            $superAdminUser->assignRole($superAdminRole);
            $superAdminUser->assignRole($adminRole);
            $superAdminUser->update(['role' => 'admin']);
        }

        $agent1 = User::where('email', 'agent1@homelist.co.in')->first();
        if ($agent1) {
            $agent1->assignRole($agentRole);
            $agent1->update(['role' => 'agent']);
        }

        $agent2 = User::where('email', 'agent2@homelist.co.in')->first();
        if ($agent2) {
            $agent2->assignRole($agentRole);
            $agent2->update(['role' => 'agent']);
        }
    }
}
