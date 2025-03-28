<?php

namespace Tests\Unit;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase; 

    public function test_account_creation_successful()
    {
        $user = User::factory()->create(); 
        //dd($user);
        $accountData = [
            'account_name' => 'John Doe',
            'account_number' => '4532015112830366', 
            'account_type' => 'personal', 
            'currency' => 'USD',
            'balance' => 1000.50, 
            'user_id' => $user->id
        ];

        $account = Account::create($accountData);

        $this->assertDatabaseHas('accounts', [
            'account_name' => 'John Doe',
            'currency' => 'USD'
        ]);
    }

    public function test_account_creation_requires_luhn_valid_number()
    {
        $account = new Account();
        $account->account_number = "1234567890123456"; // Invalid Luhn number

        $isValid = $account->isLuhnValid($account->account_number);

        $this->assertFalse($isValid, "The account number should fail Luhn validation.");
    }
}
