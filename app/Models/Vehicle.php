<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // Liste blanche pour autoriser la création
    protected $fillable = [
        'brand',
        'model',
        'category',
        'gearbox',
        'engine',
        'power_hp',
        'acceleration',
        'seats',
        'daily_price',
        'min_age',
        'min_license_years',
        'deposit',
        'status',
    ];

    // Un véhicule POSSÈDE PLUSIEURS photos
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    // Un véhicule POSSÈDE UNE SEULE photo principale
    public function primaryPhoto()
    {
        return $this->hasOne(Photo::class)->where('is_primary', true);
    }

    /**
     * Relation : Un véhicule POSSÈDE PLUSIEURS réservations.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}