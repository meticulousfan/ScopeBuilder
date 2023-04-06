<?php
namespace App\Models;

use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transactions;

class ClientProject extends Model
{
    use HasFactory, GeneratesUuid, SoftDeletes;

    protected $casts = [
        'uuid' => EfficientUuid::class,
        // 'uuid' => 'string',
    ];

    protected $guarded = [];

    protected $fillable = [
        'uuid',
        'id',
        'project_type',
        'name',
        'description',
        'code_repository_link',
        'example_projects',
        'example_projects_count',
        'type',
        'where_add',
        'web_frameworks',
        'mobile_frameworks',
        'is_draft',
        'left_step',
        'tech_type',
        'both_same_functionality',
        'project_number',
        'budget',
        'contact_developer',
        'editing_user_id',
        'user_id',
        'is_shared',
        'user_uuid',
        'assigned_to_user_id',
        'mockup',
        'mockup_url',
        'is_confirmed',
        'created_at',
        'updated_at',
        'type_id',
        'skills',
        'anonymous_user_id'
    ];


    CONST WEB_TECH = [
        'React JS'=>'React JS',
        'Angular JS'=>'Angular JS',
        'WordPress'=>'WordPress',
        'Laravel'=>'Laravel',
        'Vue JS'=>'Vue JS',
        'BigCommerce'=>'BigCommerce',
        'Ruby on Rails'=>'Ruby on Rails',
        '.Net'=>'.Net',
        'PHP'=>'PHP',
        'Python'=>'Python',
        'HTML & CSS'=>'HTML & CSS',
        'Tailwind'=>'Tailwind',
        'Squarespace'=>'Squarespace',
        'Shopify'=>'Shopify',
        'Webflow'=>'Webflow',
    ];
    CONST MOB_TECH = [
        'React Native'=>'React Native',
        'Flutter'=>'Flutter',
        'Swift'=>'Swift',
        'Java'=>'Java',
        'Kotlin'=>'Kotlin',
        'Xamarin'=>'Xamarin'
    ];

    public function getDetails($id){
        $pdetails = DB::table('client_project_details')->where('project_id', $id)->get();
        $data = array();
        if(!empty($pdetails)){
            foreach($pdetails as $pd){
                $data[$pd->type][$pd->identifier] = $pd->data;
            }
        }

        return $data;
    }

    // Belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getByUuid($uuid)
	{
        // $project = BbbMeeting::where('id', 3)->first();
        // echo $project->meeting_id,' ';
        // die;

        return ClientProject::whereUuid($uuid, 'uuid')->first();
    }

    public function transaction()
    {
        return Transactions::whereUuid($this->uuid, 'project_id')->first();
    }

    public function projectQuestions()
    {
        return $this->hasMany(ProjectQuestion::class, 'project_id', 'id');
    }

    public function projectType() {
        return $this->belongsTo(ProjectTypes::class, 'type_id');
    }

    public function skillsName() {
        $skills = json_decode($this->skills);
        if($skills) {
            $skillArray = Skills::whereIn('id', $skills)->pluck('name')->toArray();
            return implode(", ", $skillArray);
        }
    }

    public function uuidColumns()
    {
        return ['uuid'];
    }
}