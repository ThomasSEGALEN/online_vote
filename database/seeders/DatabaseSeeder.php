<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\VoteResult;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            SessionSeeder::class,
            VoteTypeSeeder::class,
            VoteSeeder::class,
            VoteAnswerSeeder::class,
            GroupSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserTitleSeeder::class,
            UserSeeder::class,
            PermissionRoleSeeder::class,
            PermissionUserSeeder::class,
            GroupUserSeeder::class,
            SessionUserSeeder::class,
            VoteResultSeeder::class,
        ]);

        User::factory()->count(50)->create();
    }
}
