<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    use HasFactory;

    protected $table = "journal_details";

    public function general_journal()
    {
        return $this->hasMany(GeneralJournal::class);
    }
}
