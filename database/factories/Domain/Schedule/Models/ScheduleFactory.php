<?php

namespace Database\Factories\Domain\Schedule\Models;

use App\Domain\Schedule\Enums\ScheduleStatusEnum;
use App\Domain\Schedule\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{

    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->name,
            'status' => $this->faker->randomElement([array_map(fn ($item) => $item->value, ScheduleStatusEnum::cases())]),
            'starts_at' => $this->faker->dateTime(),
            'ends_at' => $this->faker->dateTime(),
            'finished_at' => $this->faker->dateTime(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime()
        ];
    }
}
