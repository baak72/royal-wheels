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
        'discount',
        'deposit_amount',
        'balance',
        'invoice_link'
    ];

    // 2. Relation : Une réservation APPARTIENT À (belongsTo) un client
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 3. Relation : Une réservation APPARTIENT À (belongsTo) un véhicule
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}