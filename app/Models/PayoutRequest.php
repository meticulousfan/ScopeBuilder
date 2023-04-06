<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    use HasFactory, GeneratesUuid;
    protected $fillable = [
        'id',
        'uuid',
        'amount',
        'is_paid',
        'payment_method',
        'user_id',
        'created_at',
        'updated_at',
        'rating',
        'note'
    ];
    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid'];
    }
}
