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
                'name' => 'Oui',
                'color' => '#0000FF',
                'vote_id' => 1
            ],
            [
                'name' => 'Non',
                'color' => '#FF0000',
                'vote_id' => 1
            ],
            [
                'name' => 'Pour',
                'color' => '#0000FF',
                'vote_id' => 2
            ],
            [
                'name' => 'Contre',
                'color' => '#FF0000',
                'vote_id' => 2
            ]
        ]);
    }
}
