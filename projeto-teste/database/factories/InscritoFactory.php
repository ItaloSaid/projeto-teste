<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Inscrito;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inscrito>
 */
class InscritoFactory extends Factory
{
    protected $model = Inscrito::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'cpf' => $this->faker->unique()->randomNumber(11),
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
