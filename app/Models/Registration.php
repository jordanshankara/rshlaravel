<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'registration_code', 'full_name', 'birth_date', 'occupation',
        'whatsapp', 'address', 'height_weight', 'program_period_id',
        'health_complaints', 'clinical_details', 'bmi', 'emotion_state',
        'food_allergies', 'treatment_history', 'current_meds', 'confidence_level',
        'status', 'payment_note', 'email_sent', 'sheets_row_id',
        'submitted_at', 'confirmed_at',
        'is_present', 'present_at',
    ];

    protected $casts = [
        'birth_date'   => 'date',
        'submitted_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'present_at'   => 'datetime',
        'email_sent'   => 'boolean',
        'is_present'   => 'boolean',
    ];

    public function programPeriod()
    {
        return $this->belongsTo(ProgramPeriod::class, 'program_period_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'registration_id');
    }

    public function monitoringTokens(): HasMany
    {
        return $this->hasMany(MonitoringToken::class)->orderBy('day_number');
    }

    public function reregistrationToken(): HasOne
    {
        return $this->hasOne(ReregistrationToken::class);
    }

    // ── Helpers ─────────────────────────────────────────────────

    /** Returns "3/7" style progress string */
    public function monitoringProgress(): string
    {
        $total     = config('monitoring.days', 7);
        $completed = $this->monitoringTokens()->whereNotNull('completed_at')->count();
        return "{$completed}/{$total}";
    }

    public function isPresent(): bool
    {
        return (bool) $this->is_present;
    }
}
