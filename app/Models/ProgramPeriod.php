<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramPeriod extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'price', 'dp_amount', 'quota', 'is_active'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'program_period_id');
    }

    public function filledCount(): int
    {
        return $this->registrations()
            ->whereNotIn('status', ['CANCELLED'])
            ->count();
    }

    public function availableQuota(): int
    {
        return max(0, $this->quota - $this->filledCount());
    }
}
