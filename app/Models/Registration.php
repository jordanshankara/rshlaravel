<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'birth_date' => 'date',
        'submitted_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'email_sent' => 'boolean',
    ];

    public function programPeriod()
    {
        return $this->belongsTo(ProgramPeriod::class, 'program_period_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'registration_id');
    }
}
