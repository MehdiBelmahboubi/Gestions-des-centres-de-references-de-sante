<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosspat extends Model
{
    use HasFactory;

    protected $table = 'dosspat'; 

    protected $fillable = [
        'numero',
        'date_creation',
        'date_accouchement_prévue',
        'symptomes_actuel',
        'allergies',
        'nom_medecin',
    ];

    public function patiente()
    {
        return $this->belongsTo(Pat::class, 'patiente_id');
    }
}
