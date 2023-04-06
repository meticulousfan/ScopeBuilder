<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Questionnaire;


class ProjectTypes extends Model
{
    use HasFactory, GeneratesUuid;
    protected $fillable = [
        'uuid',
        'id',
        'name',
        'skills',
        'status',
        'parent_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid'];
    }

    public function parent()
    {
        return $this->belongsTo(ParentProjectType::class, 'parent_id');
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

}