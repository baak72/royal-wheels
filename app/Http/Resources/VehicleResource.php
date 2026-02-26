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

            // La photo principale
            'image_url' => $this->primaryPhoto ? asset($this->primaryPhoto->file_path) : null,
            
            // Le carrousel de photos
            'gallery' => $this->whenLoaded('photos', function () {
                // On transforme chaque photo brute en petit tableau avec son lien direct
                return $this->photos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'url' => asset($photo->file_path),
                        'is_primary' => (bool) $photo->is_primary,
                    ];
                });
            }),
        ];
    }
}