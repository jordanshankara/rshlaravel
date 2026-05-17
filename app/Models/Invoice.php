<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'client_name', 'invoice_date', 'payment_status',
        'total_amount', 'notes', 'author_id', 'payment_detail_id', 'registration_id',
    ];

    protected $casts = ['invoice_date' => 'date'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function paymentDetail()
    {
        return $this->belongsTo(PaymentDetail::class, 'payment_detail_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
}
