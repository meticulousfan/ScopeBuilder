<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory, GeneratesUuid;
    protected $fillable = [
        'uuid',
        'id',
        'user_id',
        'type',
        'project_id',
        'bbb_meeting_id',
        'amount',
        'stripe_id',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'uuid' => EfficientUuid::class,
        'project_id' => EfficientUuid::class,
        'bbb_meeting_id' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid','bbb_meeting_id', 'project_id'];
    }
}
