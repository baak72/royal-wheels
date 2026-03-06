<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    // 1. Autorisation du remplissage de ces colonnes lors de la création
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'status',
        'base_price',
        'total_price',
        'discount',
        'deposit_amount',
        'balance',
        'invoice_link'
    ];

    // 2. Relation : Une réservation APPARTIENT À un client
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 3. Relation : Une réservation APPARTIENT À un véhicule
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    // 4. Relation : Une réservation POSSÈDE PLUSIEURS options
    public function options()
    {
        return $this->belongsToMany(Option::class);
    }
}