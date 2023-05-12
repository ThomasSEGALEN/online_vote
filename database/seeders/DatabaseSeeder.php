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
            StatusSeeder::class,
            SessionSeeder::class,
            VoteTypeSeeder::class,
            VoteSeeder::class,
            VoteAnswerSeeder::class,
            LabelSetSeeder::class,
            AnswerSeeder::class
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

        $users->find('1')->permissions()->attach(
            $permissions->pluck('id')->toArray()
        );

        $users->each(function ($user) use ($groups, $roles) {
            $user->groups()->attach(
                $groups->random(rand(1, 2))->pluck('id')->toArray()
            );

            if ($user->id === 1) return;

            $user->permissions()->attach($roles->find('2')->permissions()->pluck('id')->toArray());
        });

        $sessions->each(function ($session) use ($groups) {
            $session->users()->attach($groups->find(rand(1, 2))->users()->pluck('id')->toArray());

            $session->votes->each(function ($vote) use ($session) {
                $vote->users()->attach($session->users()->pluck('id')->toArray());
            });
        });
    }
}
