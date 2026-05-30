<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'contact_id',
        'type',
        'user_id',
        'contacted_at',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contactedAtWib(): string
    {
        return $this->contacted_at
            ->setTimezone('Asia/Jakarta')
            ->format('d M Y, H:i');
    }
}
