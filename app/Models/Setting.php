<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tarif_per_km',
        'minimal_km',
        'maksimal_km',
        'biaya_layanan',
    ];
}
