<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Deadline extends Model
{
    protected $fillable = [
        'year',
        'stage',
        'deadline_at',
        'is_active',
        'created_by',
        'updated_by',
        'catatan',
    ];

    protected $casts = [
        'deadline_at' => 'datetime',
        'is_active' => 'boolean',
        'year' => 'integer',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Helper methods
    public function isPassed(): bool
    {
        return Carbon::now()->isAfter($this->deadline_at);
    }

    public function isActive(): bool
    {
        return $this->is_active && !$this->isPassed();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeByStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }

    public function scopePassed($query)
    {
        return $query->where('deadline_at', '<', Carbon::now());
    }
}
