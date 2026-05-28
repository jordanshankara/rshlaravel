<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoringResponse extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'monitoring_token_id',
        'category',
        'question_number',
        'answer',
    ];

    protected $casts = [
        'answer'          => 'integer',
        'question_number' => 'integer',
    ];

    public function monitoringToken(): BelongsTo
    {
        return $this->belongsTo(MonitoringToken::class);
    }
}
