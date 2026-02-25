<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    // Autorise le remplissage de ces colonnes
    protected $fillable = ['vehicle_id', 'file_path', 'is_primary'];

    // Une photo APPARTIENT À (belongsTo) un seul véhicule
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}