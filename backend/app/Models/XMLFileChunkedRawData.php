<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XMLFileChunkedRawData extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        "batch_number",
        "chunk_of_data"
    ];
}
