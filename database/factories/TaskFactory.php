<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'urgency' => $this->faker->randomElement(['low', 'medium', 'high']),
            'impact' => $this->faker->randomElement(['low', 'medium', 'high']),
            'effort' => $this->faker->randomElement(['low', 'medium', 'high']),
            'score' => 0,
            'completed' => false,
        ];
    }
}
