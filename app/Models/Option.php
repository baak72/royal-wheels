<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['name', 'price', 'pricing_type'];

    /**
     * Relation : Une option APPARTIENT À PLUSIEURS réservations.
     */
    public function reservations()
    {
        return $this->belongsToMany(Reservation::class);
    }
}