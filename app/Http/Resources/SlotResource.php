<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SlotResource extends JsonResource
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
            'company_id' => $this->company_id,
            'company' => $this->company,
            'date_time' => $this->date_time,
            'capacity' => $this->capacity,
            'duration' => $this->duration,
            'isOnLocation' => $this->isOnLocation,
        ];
    }
}
