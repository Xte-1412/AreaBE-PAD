<?php

namespace App\Models\Pusdatin;

use Illuminate\Database\Eloquent\Model;

class RekapPenilaian extends Model
{
    protected $table = 'rekap_penilaian';
    
    protected $fillable = [
        'year',
        'id_dinas',
        'nama_dinas',
        'nilai_slhd',
        'lolos_slhd',
        'nilai_penghargaan',
        'masuk_penghargaan',
        'nilai_iklh',
        'total_skor_validasi1',
        'lolos_validasi1',
        'kriteria_wtp',
        'kriteria_kasus_hukum',
        'lolos_validasi2',
        'peringkat',
        'status_akhir',
    ];
    
    protected $casts = [
        'lolos_slhd' => 'boolean',
        'masuk_penghargaan' => 'boolean',
        'lolos_validasi1' => 'boolean',
        'kriteria_wtp' => 'boolean',
        'kriteria_kasus_hukum' => 'boolean',
        'lolos_validasi2' => 'boolean',
        'nilai_slhd' => 'decimal:3',
        'nilai_penghargaan' => 'decimal:3',
        'nilai_iklh' => 'decimal:3',
        'total_skor_validasi1' => 'decimal:3',
    ];
    
    public function dinas()
    {
        return $this->belongsTo(\App\Models\Dinas::class, 'id_dinas');
    }
}
