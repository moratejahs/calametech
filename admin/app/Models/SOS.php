<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SOS extends Model
{
    protected $guarded = [];

    protected function lat(): Attribute
    {
        return Attribute::make(
            get: fn ($value): float|int => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function long(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}