<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    use HasFactory;

    protected $table = "general_journals";

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journal_detail()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
