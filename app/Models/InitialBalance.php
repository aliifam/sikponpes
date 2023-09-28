<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialBalance extends Model
{
    use HasFactory;

    protected $table = "initial_balances";

    protected $fillable = [
        'pesantren_id',
        'account_id',
        'date',
        'amount',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id')->orderby('account_code');
    }

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }

    // public function debit()
    // {
    //     return $this->account()->where('position', 'Debit');
    // }

    // public function credit()
    // {
    //     return $this->account()->where('position', 'Kredit');
    // }
}
