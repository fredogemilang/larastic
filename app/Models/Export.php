<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Export extends Model
{
    protected $fillable = [
        'type',
        'status',
        'output_path',
        'file_size',
        'csp_report',
        'scope_details',
        'based_on_export_id',
        'errors',
        'deploy_status',
        'deploy_pr_url',
        'notes',
        'triggered_by',
        'started_at',
        'completed_at',
        'deployed_at',
    ];

    protected $casts = [
        'csp_report' => 'array',
        'scope_details' => 'array',
        'errors' => 'array',
        'file_size' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'deployed_at' => 'datetime',
    ];

    public function triggeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    public function basedOn(): BelongsTo
    {
        return $this->belongsTo(self::class, 'based_on_export_id');
    }

    public function isPartial(): bool
    {
        return $this->type === 'partial';
    }

    public function isFull(): bool
    {
        return $this->type === 'full';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }
        return $this->started_at->diffForHumans($this->completed_at, true);
    }
}
