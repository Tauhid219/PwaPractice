<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_order_id',
        'provider',
        'provider_reference',
        'amount',
        'currency',
        'status',
        'payload',
        'verified_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'verified_at' => 'datetime',
    ];

    public function paymentOrder(): BelongsTo
    {
        return $this->belongsTo(PaymentOrder::class);
    }
}

