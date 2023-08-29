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
                'vote_id' => 1,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 1,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 1,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 2,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 2,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 2,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 3,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 3,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 3,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 4,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 4,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 4,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Pour',
                'color' => '#77DD76',
                'vote_id' => 5,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Contre',
                'color' => '#FF6962',
                'vote_id' => 5,
                'label_set_id' => 1,
            ],
            [
                'name' => 'Abstention',
                'color' => '#FDFD96',
                'vote_id' => 5,
                'label_set_id' => 1,
            ]
        ]);
    }
}
