<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'is_enabled' => true,
            'name' => 'valid-fake-business',
            'external_id' => 'a-valid-fake-business-external-id',
            'deduction' => 25,
        ];
    }    

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function invalidExternalId()
    {
        return $this->state(fn (array $attributes) => [
            'external_id' => 'some-invalid-external-id',
        ]);
    }
}
