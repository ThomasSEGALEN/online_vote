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
                'last_name' => 'Ségalen',
                'first_name' => 'Thomas',
                'email' => 'thomas@vote.fr',
                'password' => Hash::make('thomas'),
                'civility_id' => Civility::MAN,
                'role_id' => Role::ADMIN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'last_name' => 'Ségalen',
                'first_name' => 'Marie',
                'email' => 'marie@vote.fr',
                'password' => Hash::make('marie'),
                'civility_id' => Civility::WOMAN,
                'role_id' => Role::USER,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'last_name' => 'Ségalen',
                'first_name' => 'Jérôme',
                'email' => 'jerome@vote.fr',
                'password' => Hash::make('jerome'),
                'civility_id' => Civility::MAN,
                'role_id' => Role::USER,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
