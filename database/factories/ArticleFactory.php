<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $wordCounts = $this->faker->numberBetween(20, 50);
        return [
            'title' => $this->faker->word(),
            'content' => $this->faker->sentence($wordCounts),
            'link' => $this->faker->url(),
            'size' => $this->faker->randomNumber(),
            'wordsCount' => $wordCounts

        ];
    }
}
