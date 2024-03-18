<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
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
            'project_name' => $this->faker->sentence,
            'project_description' => $this->faker->paragraph,
            'project_status_id' => $this->faker->numberBetween(1, 5),
            'project_start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'project_estimated_end' => $this->faker->dateTimeBetween('now', '+1 year')
        ];
    }
}
