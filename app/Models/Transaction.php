<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    use HasFactory;
    protected $fillable = ['account_id', 'type', 'amount', 'description'];
    protected static function boot() {
        parent::boot();
        static::creating(function ($transaction) {
            $transaction->id = (string) \Illuminate\Support\Str::uuid();
        });
    }
}
