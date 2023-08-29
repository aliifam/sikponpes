<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public function classification()
    {
        return $this->belongsTo(AccountClassification::class);
    }

    public function initialBalance()
    {
        return $this->hasMany(InitialBalance::class);
    }

    public function journalEntry()
    {
        return $this->hasMany(JournalEntry::class);
    }

    
}
