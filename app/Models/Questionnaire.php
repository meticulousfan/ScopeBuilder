<?php

namespace App\Models;

use App\Models\Skill;
use App\Models\Question;
use App\Models\QuestionnaireSkill;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Questionnaire extends Model
{
    use HasFactory, GeneratesUuid;

    protected $table = 'questionnaires';

    protected $fillable = ['type_id', 'name', 'status', 'step', 'is_default'];

    protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

    public function uuidColumns()
    {
        return ['uuid'];
    }

    /**
     * Get all of the skills that are assigned this Questionnaire.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class)->using(QuestionnaireSkill::class);
    }

    /**
     * Get specific project type assign to Questionnaire.
     */
    public function project_types()
    {
        return $this->hasOne(ProjectTypes::class, 'id', 'type_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
