<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountClassification extends Model
{
    use HasFactory;

    protected $table = "account_classifications";

    public function account()
    {
        return $this->hasMany(Account::class);
    }

    public function parent()
    {
        return $this->belongsTo(AccountParent::class);
    }
}
