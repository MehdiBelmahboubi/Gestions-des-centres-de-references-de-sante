<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'emplacement',
        'specialité',
        'capacité',
    ];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }
    public function patient()
    {
        return $this->hasMany(Pat::class,'hospital_id');
    }
}
