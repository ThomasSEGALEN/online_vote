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
                'title' => "Séance - Proposition d'aménagement",
                'description' => "Proposition d'aménagement d'un parc communautaire.",
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'status_id' => Status::CLOSED,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance - Politique de recyclage',
                'description' => "Adoption d'une politique de recyclage renforcée.",
                'start_date' => '2023-06-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'status_id' => Status::OPEN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance - Budget alloué aux activités culturelles et artistiques',
                'description' => 'Budget alloué aux activités culturelles et artistiques.',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'status_id' => Status::CLOSED,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Séance - Réaménagement du centre-ville',
                'description' => 'Réaménagement du centre-ville.',
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'status_id' => Status::OPEN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => "Séance - Adoption d'une charte",
                'description' => "Adoption d'une charte pour l'inclusion et la diversité.",
                'start_date' => '2023-06-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'status_id' => Status::OPEN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
