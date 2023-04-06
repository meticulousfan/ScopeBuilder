<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbbMeeting extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'uuid',
        'project_id',
        'bbb_meeting_id',
        'bbb_internal_meeting_id',
        'name',
        'duration',
        'attendee_pass',
        'moderator_pass',
        'pending',
        'start_time',
        'end_time',
        'create_params',
        'pricePerMinute',
        'client_id',
        'developer_id',
    ];

    protected $casts = [
        'create_params' => 'json',
        'uuid' => EfficientUuid::class,
        'bbb_meeting_id' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid', 'bbb_meeting_id'];
    }

}
