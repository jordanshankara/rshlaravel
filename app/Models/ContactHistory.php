<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactHistory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action_type', 'description', 'affected_count', 'snapshot', 'user_id',
    ];

    protected $casts = [
        'snapshot'    => 'array',
        'created_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function actionLabel(): string
    {
        return match($this->action_type) {
            'import'       => '📥 Import CSV',
            'create'       => '➕ Tambah Kontak',
            'edit'         => '✏️ Edit Kontak',
            'bulk_edit'    => '✏️ Bulk Edit',
            'delete'       => '🗑 Hapus Kontak',
            'bulk_delete'  => '🗑 Bulk Hapus',
            default        => $this->action_type,
        };
    }

    public function createdAtWib(): string
    {
        return $this->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i');
    }
}
