<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Dyrynda\Database\Casts\EfficientUuid;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use App\Models\ProjectTypes;

class ParentProjectType extends Model
{
    use HasFactory, GeneratesUuid;
    protected $fillable = [
        'uuid',
        'id',
        'name',
        'skills',
        'status',
    ];

    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid'];
    }

    public function projectTypes()
    {
        return $this->hasMany(ProjectTypes::class, 'parent_id');
    }
}