<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbbRecording extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'uuid',
        'project_id',
        'bbb_meeting_id',
        'bbb_record_id',
        'url',
        'start_time',
    ];

    protected $casts = [
        'uuid' => EfficientUuid::class,
        'bbb_meeting_id' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid', 'bbb_meeting_id'];
    }
}
