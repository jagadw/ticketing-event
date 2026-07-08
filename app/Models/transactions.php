<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class transactions extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id', 'event_id', 'promo_id',
        'quantity', 'subtotal', 'discount', 'total',
        'payment_method', 'status', 'paid_at', 'payment_proof',
        'midtrans_order_id',  
        'snap_token',         
    ];

    protected function casts(): array
    {
        return [
            'promo_id' => 'integer',
            'quantity' => 'integer',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total'    => 'decimal:2',
            'status'   => 'string',
            'paid_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(events::class, 'event_id');
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(promos::class, 'promo_id');
    }
}
