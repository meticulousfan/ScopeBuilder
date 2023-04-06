<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionnaireSkill extends Pivot
{
    use HasFactory;

    protected $table = 'questionnaire_skill';

    protected $fillable = ['questionnaire_id', 'skill_id'];

    public function assignedBy()
    {
        return $this->belongsTo('App\Questionnaire', 'assigned_by');
    }
}
