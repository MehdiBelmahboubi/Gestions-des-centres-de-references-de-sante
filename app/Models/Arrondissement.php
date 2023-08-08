<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrondissement extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
