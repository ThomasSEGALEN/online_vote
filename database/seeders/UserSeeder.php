<?php

namespace Database\Seeders;

use App\Models\Civility;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'last_name' => 'Admin',
                'first_name' => 'Admin',
                'email' => 'admin@admin.fr',
                'password' => Hash::make('admin'),
                'civility_id' => Civility::MAN,
                'role_id' => Role::ADMIN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
