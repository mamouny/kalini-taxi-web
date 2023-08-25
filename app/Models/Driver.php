<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'tel',
        'lat',
        'lng',
        'etat_chauffeur_id',
        'etat_disponibilite',
        'user_id',
        'course_id',
        'car_id'
    ];

    public function car(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\Voiture');
    }
}
