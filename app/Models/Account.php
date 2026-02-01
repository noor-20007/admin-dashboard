<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group_id',
        'date',
        'amount',
        'reference_number',
        'status',
        'notes',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
