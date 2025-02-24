<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $guarded = [];
    use HasFactory;
    public function barangay(){
        return $this->belongTo(Barangay::class);
    }
}
