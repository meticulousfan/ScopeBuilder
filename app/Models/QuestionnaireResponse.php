<?php

namespace App\Models;

use App\Models\Question;
use App\Models\UserQuestionnaire;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionnaireResponse extends Model
{
    use HasFactory, GeneratesUuid;

    protected $table = 'questionnaire_responses';

    protected $fillable = ['user_questionnaire_id', 'question_id', 'response', 'step'];

    protected $casts = [
        'response' => 'array',
        'uuid' => EfficientUuid::class,
    ];

    public function getFieldsAttribute()
    {
        return $this->response;
    }

    public function uuidColumns()
    {
        return ['uuid'];
    }

    public function user_questionnaire()
    {
        return $this->belongsTo(UserQuestionnaire::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
