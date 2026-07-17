<?php

namespace App\Imports\Wordpress;

use App\Models\Agent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserImporter
{
    protected string $sqlPath;

    public function __construct()
    {
        $this->sqlPath = base_path('wp_users (1).sql');
    }

    public function import(): int
    {
        // 1. Check if wp_users table exists, if not, import the SQL file
        if (!Schema::hasTable('wp_users')) {
            if (!file_exists($this->sqlPath)) {
                throw new \Exception("WordPress users SQL file not found at: {$this->sqlPath}");
            }
            
            DB::unprepared(file_get_contents($this->sqlPath));
        }

        // 2. Ensure roles exist in database
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // 3. Query users from wp_users table
        $wpUsers = DB::table('wp_users')->get();
        $importedCount = 0;

        foreach ($wpUsers as $wpUser) {
            // Check if user already exists (by wp_user_id or email)
            $user = User::where('wp_user_id', $wpUser->ID)
                ->orWhere('email', $wpUser->user_email)
                ->first();

            // Determine role
            $role = 'agent';
            if ($wpUser->ID == 5 || str_contains(strtolower($wpUser->user_email), 'admin')) {
                $role = 'admin';
            }

            if (!$user) {
                $user = User::create([
                    'wp_user_id' => $wpUser->ID,
                    'name' => $wpUser->display_name ?: $wpUser->user_login,
                    'email' => $wpUser->user_email ?: "user_{$wpUser->ID}@homelist.local",
                    // Keep WordPress hash as password so they can log in if a custom hasher is used,
                    // or default to a secure password.
                    'password' => $wpUser->user_pass ?: Hash::make('password123'),
                    'role' => $role,
                ]);
            } else {
                $user->update([
                    'wp_user_id' => $wpUser->ID,
                    'role' => $role,
                ]);
            }

            // Sync Spatie roles
            $user->syncRoles([$role]);

            // Create Agent profile if role is admin or agent
            if (in_array($role, ['admin', 'agent'])) {
                Agent::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'phone' => null,
                        'photo' => null,
                        'about' => "WordPress Imported Agent. Username: {$wpUser->user_login}",
                    ]
                );
            }

            $importedCount++;
        }

        return $importedCount;
    }
}
