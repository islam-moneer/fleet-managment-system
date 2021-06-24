<?php

namespace Database\Factories;

use App\Models\Seat;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Seat::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'trip_id' => Trip::factory(),
            'unique_id' => uniqid()
        ];
    }
}
