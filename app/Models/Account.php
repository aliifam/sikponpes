<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = "accounts";

    protected $fillable = [
        'classification_id',
        'account_code',
        'account_name',
        'position',
    ];

    public function classification()
    {
        return $this->belongsTo(AccountClassification::class);
    }

    public function parent()
    {
        return $this->classification->belongsTo(AccountParent::class);
    }

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class, 'pesantren_id');
    }

    public function initialBalance()
    {
        return $this->hasMany(InitialBalance::class);
    }

    public function journal()
    {
        return $this->hasMany(GeneralJournal::class);
    }
}
