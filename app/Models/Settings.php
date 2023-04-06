<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    use GeneratesUuid;
    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

    protected $fillable = [
        'pricePerCallMinute',
        'defaultQuestionPrice',
        'minimumDepositAmount',
        'maximumDepositAmount',
    ];
}
