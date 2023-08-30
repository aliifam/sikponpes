<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesantren extends Model
{
    use HasFactory;

    protected $table = "pesantrens";

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accountParent()
    {
        return $this->hasMany(AccountParent::class);
    }
}
