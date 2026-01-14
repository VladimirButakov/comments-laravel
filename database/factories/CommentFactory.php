<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => fake()->paragraph(),
            'parent_id' => null,
        ];
    }

    public function withParent(?Comment $parent = null): self
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent ? $parent->id : Comment::factory(),
        ]);
    }
}




