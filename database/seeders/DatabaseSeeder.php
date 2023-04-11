<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Session;
use App\Models\User;
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
        $this->call([
            CivilitySeeder::class,
            RoleSeeder::class,
            GroupSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            SessionSeeder::class
        ]);

        User::factory()->count(150)->create();

        $users = User::all();
        $roles = Role::all();
        $groups = Group::all();
        $permissions = Permission::all();
        $sessions = Session::all();

        $roles->find('1')->permissions()->attach(
            $permissions->pluck('id')->toArray()
        );
        $roles->find('2')->permissions()->attach([1, 6, 11, 16]);

        $users->find('1')->permissions()->attach(
            $permissions->pluck('id')->toArray()
        );

        $users->each(function ($user) use ($groups, $permissions) {
            $user->groups()->attach(
                $groups->random(rand(1, 2))->pluck('id')->toArray()
            );

            if ($user->id === 1) return;

            $user->permissions()->attach(
                $permissions->random(rand(1, 20))->pluck('id')->toArray()
            );
        });

        $sessions->each(function ($session) use ($groups) {
            $session->users()->attach($groups->find(rand(1, 2))->users()->pluck('id')->toArray());
        });
    }
}
