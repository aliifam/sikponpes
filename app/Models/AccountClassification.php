<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountClassification extends Model
{
    use HasFactory;

    protected $table = "account_classifications";

    protected $fillable = [
        'parent_id',
        'pesantren_id',
        'classification_code',
        'classification_name',
    ];

    public function account()
    {
        return $this->hasMany(Account::class);
    }

    public function parent()
    {
        return $this->belongsTo(AccountParent::class);
    }

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
}
