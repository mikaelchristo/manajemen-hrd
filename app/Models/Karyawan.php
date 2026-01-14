<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';

    protected $fillable = [
        'nikKry',
        'namaKaryawan',
        'nikKtp',
        'unit',
        'gol',
        'profesi',
        'statusPegawai',
        'tempatLahir',
        'tglLahir',
        'tglMulaiKerja',
        'jenisKelamin',
        'skTetap',
        'pendidikan',
        'tamatan',
        'noHp',
        'email',
        'alamat',
    ];

    protected $casts = [
        'tglLahir' => 'date',
        'tglMulaiKerja' => 'date',
        // skTetap adalah nomor SK (string), bukan tanggal
    ];

    /**
     * Get umur dalam tahun
     */
    public function getUmurTahunAttribute()
    {
        if ($this->tglLahir) {
            return (int) \Carbon\Carbon::parse($this->tglLahir)->diffInYears(now());
        }
        return null;
    }

    /**
     * Get umur dalam bulan
     */
    public function getUmurBulanAttribute()
    {
        if ($this->tglLahir) {
            return (int) \Carbon\Carbon::parse($this->tglLahir)->diffInMonths(now());
        }
        return null;
    }
}
