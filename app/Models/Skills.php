<?php

namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    use HasFactory, GeneratesUuid;
    protected $fillable = [
        'uuid',
        'id',
        'name',
        'status',
        'user_id',
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
}
