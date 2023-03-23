<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['name' => 'viewAnyUsers'],
            ['name' => 'viewUsers'],
            ['name' => 'createUsers'],
            ['name' => 'updateUsers'],
            ['name' => 'deleteUsers'],
            ['name' => 'viewAnyRoles'],
            ['name' => 'viewRoles'],
            ['name' => 'createRoles'],
            ['name' => 'updateRoles'],
            ['name' => 'deleteRoles'],
            ['name' => 'viewAnyGroups'],
            ['name' => 'viewGroups'],
            ['name' => 'createGroups'],
            ['name' => 'updateGroups'],
            ['name' => 'deleteGroups'],
            ['name' => 'viewAnySessions'],
            ['name' => 'viewSessions'],
            ['name' => 'createSessions'],
            ['name' => 'updateSessions'],
            ['name' => 'deleteSessions']
        ]);
    }
}
