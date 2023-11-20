<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralJournal extends Model
{
    use HasFactory;

    protected $table = "general_journals";

    protected $fillable = [
        'id',
        'journal_detail_id',
        'account_id',
        'pesantren_id',
        'debit',
        'credit',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function detail()
    {
        return $this->belongsTo(JournalDetail::class, 'journal_detail_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function santri()
    {
        return $this->belongsTo(Santri::class);
    }
}
