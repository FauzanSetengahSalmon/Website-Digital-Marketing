<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaKwt extends Model
{
    protected $fillable = ['kwt_id', 'nama_anggota', 'no_hp'];
    public function kwt()
    {
        return $this->belongsTo(User::class, 'kwt_id');
    }
}
