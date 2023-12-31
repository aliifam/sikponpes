<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

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

    // public function employee()
    // {
    //     return $this->hasMany(Employee::class);
    // }

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function parents(): HasMany
    {
        return $this->hasMany(AccountParent::class);
    }

    public function classifications(): HasMany
    {
        return $this->hasMany(AccountClassification::class);
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function initialBalances(): HasMany
    {
        return $this->hasMany(InitialBalance::class);
    }

    public function journalDetails(): HasMany
    {
        return $this->hasMany(JournalDetail::class);
    }

    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class, 'id');
    }

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class);
    }

    public function perusahaans(): HasMany
    {
        return $this->hasMany(Perusahaan::class);
    }
}
