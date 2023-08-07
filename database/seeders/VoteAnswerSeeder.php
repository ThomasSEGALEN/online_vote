<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoteAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vote_answers')->insert([
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 1
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 1
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 1
            ],
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 2
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 2
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 2
            ],
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 3
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 3
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 3
            ],
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 4
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 4
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 4
            ],
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 5
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 5
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 5
            ]
        ]);
    }
}
