<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class events extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'description',
        'image',
        'event_date',
        'location',
        'ticket_price',
        'quota',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'ticket_price' => 'decimal:2',
            'quota' => 'integer',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(transactions::class, 'event_id');
    }
}
