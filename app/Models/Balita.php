<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    use HasFactory;
    protected $table = 'balita';

    protected $fillable = [
        'kode_balita',
        'nama', 
        'jenis_kelamin', 
        'tanggal_lahir',
        'nama_orang_tua', 
        'no_hp', 
        'alamat',
        'posyandu_id', 
        'created_by', 
        'is_active',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    // ── Relations ─────────────────────────────────────────────
    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pengukuran()
    {
        return $this->hasMany(Pengukuran::class)->orderBy('tanggal_ukur');
    }
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function latestPengukuran()
    {
        return $this->hasOne(Pengukuran::class)->latestOfMany('tanggal_ukur');
    }

    public function getUmurBulanAttribute()
    {
        $months = Carbon::parse($this->tanggal_lahir)->diffInMonths(now());
        $years = floor($months / 12);
        $remain = $months % 12;

        if ($years > 0) {
            return "$years thn $remain bln";
        }

        return "$months bln";
    }
    public function getUmurFormattedAttribute(): string
    {
        $bulan = Carbon::parse($this->tanggal_lahir)->diffInMonths(now());
        $tahun = intdiv($bulan, 12);
        $sisa  = $bulan % 12;
        if ($tahun > 0) {
            return $sisa > 0 ? "{$tahun} thn {$sisa} bln" : "{$tahun} tahun";
        }
        return "{$bulan} bulan";
    }
 
    public function getJenisKelaminLabelAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }
    public static function generateKode(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? (intval(substr($last->kode_balita, 4)) + 1) : 1;
        return 'BLT-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    public function scopeByPosyandu($query, $posyandu_id)
    {
        return $query->where('posyandu_id', $posyandu_id);
    }
    public function pengukuranTerakhir()
    {
        return $this->hasOne(Pengukuran::class)->latestOfMany('tanggal_ukur');
    }
}
