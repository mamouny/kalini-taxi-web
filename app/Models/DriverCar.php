<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverCar extends Model
{
    protected $table = "car_documents";

    protected $fillable = [
        "driver_id_firebase",
        "car_assurance_photo",
        "car_vignette_photo",
        "car_carte_grise_photo",
    ];
}
