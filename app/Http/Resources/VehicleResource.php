<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Construction de la réponse JSON "propre"
        return [
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
            'category' => $this->category,
            'gearbox' => $this->gearbox,
            'engine' => $this->engine,
            'power_hp' => $this->power_hp,
            'acceleration' => $this->acceleration,
            'seats' => $this->seats,

            'daily_price' => (float) $this->daily_price,
            'deposit' => (float) $this->deposit,
            
            'min_age' => $this->min_age,
            'min_license_years' => $this->min_license_years,
            'status' => $this->status,

            // Extraction de l'image du sous-tableau et utilisation de la fonction asset() pour générer le lien complet
            'image_url' => $this->primaryPhoto ? asset($this->primaryPhoto->file_path) : null,
            
            // Remarque : 'created_at' et 'updated_at' sont volontairement omis.
        ];
    }
}