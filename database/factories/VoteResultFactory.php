<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VoteResult>
 */
class VoteResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'answer_id' => rand(1, 3),
            'user_id' => rand(3, 50),
            'vote_id' => rand(1, 5),
            'created_at' => fake()->dateTimeBetween('2023-08-01 12:00:00', '2023-10-01 18:00:00'),
            'updated_at' => fake()->dateTimeBetween('2023-08-01 12:00:00', '2023-10-01 18:00:00')
        ];
    }
}
