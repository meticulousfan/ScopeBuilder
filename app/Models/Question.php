<?php

namespace App\Models;

use App\Models\Questionnaire;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory, GeneratesUuid;

    protected $table = 'questions';

    protected $fillable = ['questionnaire_id', 'status', 'step', 'position'];

    protected $casts = [
        'fields' => 'array',
        'uuid' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid'];
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }
}
