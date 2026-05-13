<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    protected $fillable = ['bank_name', 'account_number', 'account_name', 'is_default'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'payment_detail_id');
    }
}
