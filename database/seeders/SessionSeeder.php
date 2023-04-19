<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sessions')->insert([
            [
                'title' => 'Séance 1',
                'description' => 'Description de la séance 1',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-01-01 18:00:00',
                'status_id' => Status::OPEN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance 2',
                'description' => 'Description de la séance 2',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'status_id' => Status::OPEN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance 3',
                'description' => 'Description de la séance 3',
                'start_date' => '2023-02-16 12:00:00',
                'end_date' => '2023-05-03 18:00:00',
                'status_id' => Status::CLOSED,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
