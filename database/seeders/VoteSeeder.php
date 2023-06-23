<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\VoteType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('votes')->insert([
            [
                'title' => 'Séance 1 - Vote 1',
                'description' => 'Description du vote 1',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-01-01 18:00:00',
                'session_id' => 1,
                'status_id' => Status::OPEN,
                'type_id' => VoteType::PUBLIC,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance 1 - Vote 2',
                'description' => 'Description du vote 2',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'session_id' => 1,
                'status_id' => Status::CLOSED,
                'type_id' => VoteType::SECRET,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance 2 - Vote 1',
                'description' => 'Description du vote 1',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-01-01 18:00:00',
                'session_id' => 2,
                'status_id' => Status::OPEN,
                'type_id' => VoteType::PUBLIC,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance 2 - Vote 2',
                'description' => 'Description du vote 2',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-01-01 18:00:00',
                'session_id' => 2,
                'status_id' => Status::OPEN,
                'type_id' => VoteType::SECRET,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance 3 - Vote 1',
                'description' => 'Description du vote 1',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-01-01 18:00:00',
                'session_id' => 3,
                'status_id' => Status::CLOSED,
                'type_id' => VoteType::SECRET,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
