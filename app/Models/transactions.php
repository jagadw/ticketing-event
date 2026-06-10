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
        'user_id',
        'event_id',
        'promo_id',
        'ticket_quantity',
        'total_price',
        'payment_status',
        'payment_method',
    ];

    protected function casts(): array
    {
        return [
            'promo_id' => 'integer',
            'ticket_quantity' => 'integer',
            'total_price' => 'decimal:2',
            'payment_status' => 'string',
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
