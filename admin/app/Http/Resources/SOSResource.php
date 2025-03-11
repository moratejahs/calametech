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
            'status' => $this->status,
            'type' => $this->type,
            'image_path' => $this->image_path,
        ];
    }
}
