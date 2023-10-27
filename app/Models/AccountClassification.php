<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountClassification extends Model
{
    use HasFactory;

    protected $table = "account_classifications";

    protected $fillable = [
        'parent_id',
        'classification_code',
        'classification_name',
    ];

    public function account()
    {
        return $this->hasMany(Account::class, 'classification_id');
    }

    public function parent()
    {
        return $this->belongsTo(AccountParent::class, 'parent_id');
    }

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class, 'pesantren_id');
    }
}
