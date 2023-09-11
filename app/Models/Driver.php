<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'tel',
        'photo',
        'lat',
        'lng',
        'etat_chauffeur_id',
        'etat_disponibilite',
        'user_id',
        'course_id',
        'car_id'
    ];

}
