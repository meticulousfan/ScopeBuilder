<?php

namespace App\Models;

use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserQuestionnaire extends Pivot
{
    use HasFactory, GeneratesUuid;

    protected $table = 'user_questionnaire';

    protected $fillable = ['id', 'user_id', 'questionnaire_id', 'status', 'uuid', 'total'];

    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid'];
    }

    public function assignedBy()
    {
        return $this->belongsTo('App\User', 'assigned_by');
    }

    public function questionnaire_responses()
    {
        return $this->hasMany(QuestionnaireResponse::class, 'user_questionnaire_id');
    }

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }
}
