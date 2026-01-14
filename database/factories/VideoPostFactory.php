<?php

namespace Database\Factories;

use App\Models\VideoPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoPostFactory extends Factory
{
    protected $model = VideoPost::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}




