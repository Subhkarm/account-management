<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Helpers\Luhn;
use App\Helpers\LuhnValidator;

class Account extends Model {
    use HasFactory, SoftDeletes, HasUuids;
    protected $fillable = ['user_id', 'account_name', 'account_number', 'account_type', 'currency', 'balance'];

    protected static function boot() {
        parent::boot();
        static::creating(function ($account) {
            $account->id = (string) \Illuminate\Support\Str::uuid();
            $account->account_number = Luhn::generateAccountNumber();
        });
    }

    public function isLuhnValid()
    {
        return LuhnValidator::isValid($this->account_number);
    }
}
