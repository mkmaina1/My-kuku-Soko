<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MpesaPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkout_request_id',
        'merchant_request_id',
        'order_id',
        'user_id',
        'phone_number',
        'amount',
        'account_reference',
        'transaction_desc',
        'status',
        'mpesa_receipt_number',
        'transaction_date',
        'result_desc',
        'callback_data'
    ];

    protected $casts = [
        'callback_data' => 'array',
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
