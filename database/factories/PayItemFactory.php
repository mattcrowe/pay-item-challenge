<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PayItem>
 */
class PayItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'external_id' => Str::random(10),
            'amount_paid_in_cents' => 1000,
            'time_worked' => 100,
            'hourly_rate_in_cents' => 1000,
            'paid_at' => '2022-10-18',
        ];
    }        
}
