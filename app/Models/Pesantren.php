<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function parents(): HasMany
    {
        return $this->hasMany(AccountParent::class);
    }

    public function classifications(): HasManyThrough
    {
        return $this->hasManyThrough(AccountClassification::class, AccountParent::class);
    }

    public function accounts(): HasManyThrough
    {
        return $this->hasManyThrough(Account::class, AccountParent::class, 'pesantren_id', 'classification_id');
    }
}
