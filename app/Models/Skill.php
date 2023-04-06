<?php

namespace App\Models;

use App\Models\Questionnaire;
use App\Models\QuestionnaireSkill;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
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

    /**
     * Get all of the questionnaires that are assigned this skills.
     */
    public function questionnaires()
    {
        return $this->belongsToMany(Questionnaire::class)->using(QuestionnaireSkill::class);
    }
}
