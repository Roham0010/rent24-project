<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'last_number'
    ];
}
