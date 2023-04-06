<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Casts\EfficientUuid;
use Dyrynda\Database\Support\GeneratesUuid;
use DB;


class AnonymousClientProject extends Model
{
    use HasFactory, GeneratesUuid;

	protected $guarded = [];

	protected $casts = [
        'uuid' => EfficientUuid::class,
    ];

	CONST WEB_TECH = ClientProject::WEB_TECH;
    CONST MOB_TECH = ClientProject::MOB_TECH;


    public function registerClientToAnonymousProject($user, $project_ref)
    {
        $user_id = $user->id;
        $user_uuid = $user->uuid();

        DB::transaction(function() use($user_id, $user_uuid, $project_ref) {

            $anonymousProject = AnonymousClientProject::whereUuid($project_ref)->first();

			$anonymousProjectDetails = AnonymousClientProjectDetail::where('project_id', $anonymousProject->id)->get();

			if($anonymousProject && $anonymousProjectDetails) {

				// create client project
				$anonymousProject = $anonymousProject->toArray();
				$anonymous_project_id = $anonymousProject['id'];
				$anonymousProject['user_id'] = $user_id;
				$anonymousProject['user_uuid'] = $user_uuid;

				unset($anonymousProject['id'], $anonymousProject['uuid']);	

				$clientProject = ClientProject::create($anonymousProject);
	
				// create client project details
				foreach($anonymousProjectDetails as $detail) {

					$detail = $detail->toArray();
					$detail['project_id'] = $clientProject->id;
					unset($detail['id']);
					ClientProjectDetail::create($detail);
				}

				// delete anonymous project
				AnonymousClientProject::where('id', $anonymous_project_id)->delete();

				// delete anonymous project details
				AnonymousClientProjectDetail::where('project_id', $anonymous_project_id)->delete();
			}
        });
    }


}
