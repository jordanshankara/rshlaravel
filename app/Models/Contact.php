<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'gender', 'address', 'age',
        'health_complaint', 'info_source', 'source_file', 'notes',
        'last_contacted_at', 'last_contacted_type',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
        'age'               => 'integer',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(ContactLog::class)->orderByDesc('contacted_at');
    }

    /** Phone number formatted for wa.me link (strip non-digits) */
    public function waPhone(): string
    {
        return preg_replace('/\D/', '', $this->phone);
    }

    /** Source file badge label */
    public function sourceBadgeLabel(): string
    {
        return match (true) {
            str_contains($this->source_file ?? '', 'Webinar') => 'Webinar',
            str_contains($this->source_file ?? '', 'Talkshow') => 'Talkshow',
            str_contains($this->source_file ?? '', 'Gereja') => 'Gereja',
            default => $this->source_file ?? '—',
        };
    }

    /** Source file badge color */
    public function sourceBadgeClass(): string
    {
        return match (true) {
            str_contains($this->source_file ?? '', 'Webinar') => 'bg-blue-100 text-blue-700',
            str_contains($this->source_file ?? '', 'Talkshow') => 'bg-purple-100 text-purple-700',
            str_contains($this->source_file ?? '', 'Gereja') => 'bg-orange-100 text-orange-700',
            default => 'bg-gray-100 text-gray-600',
        };
    }
}
