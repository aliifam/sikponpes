<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesantren extends Model
{
    use HasFactory;

    protected $table = "pesantrens";

    protected $fillable = [
        'name',
        'address',
        'phone_number',
        'is_active',
        'user_id'
    ];

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function accountClassifications(): HasMany
    {
        return $this->hasMany(AccountClassification::class);
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class);
    }
}
