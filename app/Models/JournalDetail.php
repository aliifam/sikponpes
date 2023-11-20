<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    use HasFactory;

    protected $table = "journal_details";

    //primary key is string type

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $orderBy = 'date';
    protected $orderDirection = 'DESC';

    protected $fillable = [
        'id',
        'receipt',
        'date',
        'description',
        'pesantren_id',
        'journals'
    ];

    //journals is json type containing account_id, position, and amount. structure from general journals table
    protected $casts = [
        'journals' => 'array',
        'date' => 'datetime'
    ];

    public function general_journal()
    {
        return $this->hasMany(GeneralJournal::class);
    }

    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
}
