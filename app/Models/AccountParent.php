<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountParent extends Model
{
    use HasFactory;

    protected $table = "account_parents";

    protected $fillable = [
        'pesantren_id',
        'parent_code',
        'parent_name',
    ];

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function classification()
    {
        return $this->hasMany(AccountClassification::class);
    }
}
