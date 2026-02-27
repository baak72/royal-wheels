<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    // On autorise tout ce qui vient de notre Seeder / formulaire
    protected $fillable = [
        'brand', 'model', 'category', 'gearbox', 'engine', 
        'power_hp', 'acceleration', 'seats', 'daily_price', 
        'min_age', 'min_license_years', 'deposit', 'status'
    ];

    // Un véhicule POSSÈDE PLUSIEURS (hasMany) photos
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    // Un véhicule POSSÈDE UNE SEULE (hasOne) photo principale
    public function primaryPhoto()
    {
        return $this->hasOne(Photo::class)->where('is_primary', true);
    }

    /**
     * Relation : Un véhicule POSSÈDE PLUSIEURS (hasMany) réservations.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}