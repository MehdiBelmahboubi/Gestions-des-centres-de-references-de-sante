<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pat extends Model
{
    use HasFactory;

    protected $table = 'pat'; 

    protected $fillable = [
        'cin',
        'name',
        'date_naissance',
        'adresse',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class,'hospital_id');
    }
    public function dossier()
    {
        return $this->hasMany(Dosspat::class, 'patiente_id');
    }
}

