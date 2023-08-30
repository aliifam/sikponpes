<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialBalance extends Model
{
    use HasFactory;

    protected $table = "initial_balances";

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
