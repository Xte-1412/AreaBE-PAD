<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'nama_region',
        'type',
        'parent_id',
        'kategori',
        'has_pesisir',
    ];

    protected $casts = [
        'has_pesisir' => 'boolean',
    ];

    /**
     * Get the parent region (for kabupaten/kota).
     */
    public function parent()
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    /**
     * Get the child regions (for provinsi).
     */
    public function children()
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    /**
     * Get the dinas for this region.
     */
    public function dinas()
    {
        return $this->hasOne(\App\Models\Dinas::class, 'region_id');
    }
}
