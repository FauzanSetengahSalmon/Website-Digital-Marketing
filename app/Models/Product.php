<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Tambahkan ini
        'nama_produk', 
        'harga', 
        'stok', 
        'satuan', 
        'foto_produk'
    ];

    // Relasi ke User (KWT)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->foto_produk && Storage::disk('public')->exists($this->foto_produk)) {
            return asset('storage/' . $this->foto_produk);
        }
        return null; // Balikin null biar di Blade bisa kita kasih icon cantik
    }
}