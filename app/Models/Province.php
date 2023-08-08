<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function Arrondissement()
    {
        return $this->hasMany(Region::class);
    }
}