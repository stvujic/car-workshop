<?php

namespace Database\Factories;

use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WorkshopFactory extends Factory
{
    protected $model = Workshop::class;

    public function definition(): array
    {
        $name = $this->faker->company . ' Car Service';

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'city' => $this->faker->randomElement(['Novi Sad', 'Beograd', 'Nis', 'Subotica']),
            'address' => $this->faker->streetAddress(),
            'phone' => $this->faker->phoneNumber(),
            'description' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['approved', 'approved', 'approved', 'pending']),
        ];
    }
}
