<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbbDefaultCreateSetting extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'uuid',
        'record',
        'autoStartRecording',
        'allowStartStopRecording',
        'maxParticipants',
        'bannerText',
        'bannerColor',
    ];

    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

}
