<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BbbDefaultJoinSetting extends Model
{
    use HasFactory, GeneratesUuid;

    protected $fillable = [
        'uuid',
        'duration',
        'userdata__bbb_record_video',
    ];

    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

}
