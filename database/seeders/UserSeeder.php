<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = collect([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password123#*'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg'
            ],
            [
                'name' => 'quest',
                'email' => 'quest@quest.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg'
            ],
            [
                'name' => 'user',
                'email' => 'user@user.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg'
            ]
        ]);

        $users->each(function ($user) {
            User::insert($user);
        });

        Permission::create(['name' => 'see users']);
        Permission::create(['name' => 'store settings']);
        Permission::create(['name' => 'change products']);
        Permission::create(['name' => 'see single products']);

        $admin = User::where('email', 'admin@admin.com')->first();

        $admin->givePermissionTo('see users');
        $admin->givePermissionTo('store settings');
        $admin->givePermissionTo('change products');
        $admin->givePermissionTo('see single products');

    }
}
