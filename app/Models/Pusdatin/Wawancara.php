<?php

namespace App\Models\Pusdatin;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dinas;
use App\Models\User;

class Wawancara extends Model
{
    protected $table = 'wawancara';

    protected $fillable = [
        'year',
        'id_dinas',
        'nilai_wawancara',
        'catatan',
        'status',
        'is_finalized',
        'finalized_at',
        'finalized_by'
    ];

    protected $casts = [
        'year' => 'integer',
        'nilai_wawancara' => 'decimal:2',
        'is_finalized' => 'boolean',
        'finalized_at' => 'datetime'
    ];

    /**
     * Relationship ke Dinas
     */
    public function dinas()
    {
        return $this->belongsTo(Dinas::class, 'id_dinas');
    }

    /**
     * Relationship ke User (finalized_by)
     */
    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    /**
     * Scope untuk filter by year
     */
    public function scopeYear($query, $year)
    {
        return $query->where('year', $year);
    }

    /**
     * Scope untuk filter finalized
     */
    public function scopeFinalized($query)
    {
        return $query->where('is_finalized', true);
    }
}
