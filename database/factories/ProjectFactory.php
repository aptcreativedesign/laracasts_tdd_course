<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => function() {
                // create a user and return the ID
                return User::factory()->create()->id;
            },
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
    }
}
