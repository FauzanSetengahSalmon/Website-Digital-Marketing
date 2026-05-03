<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk', 
        'harga', 
        'stok', 
        'satuan', 
        'foto_produk'
    ];

    // Aksesor untuk mempermudah pemanggilan foto di Blade
    public function getImageUrlAttribute()
    {
        if ($this->foto_produk && Storage::disk('public')->exists($this->foto_produk)) {
            return asset('storage/' . $this->foto_produk);
        }
        return 'https://placehold.co/400x400?text=No+Image';
    }
}