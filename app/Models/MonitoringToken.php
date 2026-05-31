<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonitoringToken extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'registration_id',
        'day_number',
        'token',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'created_at'   => 'datetime',
    ];

    // ── Relations ───────────────────────────────────────────────

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(MonitoringResponse::class);
    }

    // ── Helpers ─────────────────────────────────────────────────

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function emosiScore(): int
    {
        return (int) $this->responses()
            ->where('category', 'EMOSI')
            ->sum('answer');
    }

    public function fisikScore(): int
    {
        return (int) $this->responses()
            ->where('category', 'FISIK')
            ->sum('answer');
    }

    public function emosiLevel(): array
    {
        return $this->getLevel($this->emosiScore());
    }

    public function fisikLevel(): array
    {
        return $this->getLevel($this->fisikScore());
    }

    private function getLevel(int $score): array
    {
        foreach (config('monitoring.scoring.levels') as $level) {
            if ($score >= $level['min'] && $score <= $level['max']) {
                return $level;
            }
        }
        return config('monitoring.scoring.levels')[2]; // fallback: lowest
    }

    /**
     * Generate a human-readable, secure URL for this monitoring token.
     * Format: /monitoring/{registration_id}/{day_number}/{10-char HMAC}
     * Example: /monitoring/4/1/a3b7f2c9d4
     */
    public function friendlyUrl(): string
    {
        $sig = substr(
            hash_hmac('sha256', $this->registration_id . '-' . $this->day_number, config('app.key')),
            0, 10
        );
        return url("/monitoring/{$this->registration_id}/{$this->day_number}/{$sig}");
    }

    // ── Scopes ──────────────────────────────────────────────────

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('completed_at');
    }
}
