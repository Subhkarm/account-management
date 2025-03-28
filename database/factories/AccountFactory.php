<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        return [
            'account_name' => $this->faker->name(), 
            'account_number' => fake()->unique()->numerify('############'),
            'account_type' => 'personal',
            'currency' => 'USD',
            'balance' => fake()->randomFloat(2, 100, 10000),
            'user_id' => 1,
        ];
    }
}
