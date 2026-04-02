<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengukuran extends Model
{
    use HasFactory;
    protected $table = 'pengukuran';
    protected $fillable = [
        'balita_id',
        'tanggal_ukur',
        'umur_bulan',
        'berat_badan',
        'tinggi_badan',

        'kategori_bbu',
        'kategori_tbu',
        'kategori_bbtb',

        'status_stunting',

        'catatan',
        'user_id',
    ];

    protected $casts = [
        'tanggal_ukur' => 'date',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
    ];

    protected $appends = [
        'status_label',
        'status_color',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function balita()
    {
        return $this->belongsTo(Balita::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR STATUS STUNTING
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute()
    {
        return [
            'normal' => 'Normal',
            'berisiko' => 'Berisiko Gangguan Pertumbuhan',
            'stunting' => 'Stunting',
        ][$this->status_stunting] ?? '-';
    }

    public function getStatusColorAttribute()
    {
        return [
            'normal' => 'green',
            'berisiko' => 'yellow',
            'stunting' => 'red',
        ][$this->status_stunting] ?? 'gray';
    }
}