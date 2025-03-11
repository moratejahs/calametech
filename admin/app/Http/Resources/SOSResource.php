<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\SOS
 */
class SOSResource extends JsonResource
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
            'lat' => $this->lat,
            'long' => $this->long,
            'address' => $this->address,
            'status' => $this->status,
            'type' => $this->type,
            'image' => $this->image_path,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
