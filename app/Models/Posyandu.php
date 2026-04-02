<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    protected $table = 'posyandu';
    protected $fillable = [
        'nama', 
        'kode', 
        'kelurahan', 
        'kecamatan',
        'kabupaten', 
        'alamat', 
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function balita()
    {
        return $this->hasMany(Balita::class);
    }
    public function getTotalBalitaAttribute()
    {
        return $this->balita()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
