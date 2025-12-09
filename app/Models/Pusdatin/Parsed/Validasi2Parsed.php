<?php

namespace App\Models\Pusdatin\Parsed;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pusdatin\Validasi2;

class Validasi2Parsed extends Model
{
    protected $table = 'validasi_2_parsed';
    
    protected $fillable = [
        'validasi_2_id',
        'id_dinas',
        'nama_dinas',
        'Nilai_Penghargaan',
        'Nilai_IKLH',
        'Total_Skor',
        'Kriteria_WTP',
        'Kriteria_Kasus_Hukum',
        'status_validasi',
        'catatan',
    ];
    
    protected $casts = [
        'Kriteria_WTP' => 'boolean',
        'Kriteria_Kasus_Hukum' => 'boolean',
        'Nilai_Penghargaan' => 'decimal:3',
        'Nilai_IKLH' => 'decimal:3',
        'Total_Skor' => 'decimal:3',
    ];

    public function validasi2()
    {
        return $this->belongsTo(Validasi2::class, 'validasi_2_id');
    }
    
    public function dinas()
    {
        return $this->belongsTo(\App\Models\Dinas::class, 'id_dinas');
    }
}
