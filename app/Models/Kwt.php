<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kwt extends Model
{
    Use HasFactory;

    protected $fillable = [
        'nama_kwt',
        'alamat',
        'kontak',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this-> belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
