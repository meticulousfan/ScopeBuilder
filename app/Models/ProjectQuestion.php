<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;

class ProjectQuestion extends Model
{
    use HasFactory, GeneratesUuid;

    protected $table = 'project_questions';

    protected $guarded = [];

    protected $casts = [
        'fields' => 'array',
        'uuid' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid'];
    }
}
