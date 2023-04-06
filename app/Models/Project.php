<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public static function getByUuid($uuid)
	{
        // $project = BbbMeeting::where('id', 3)->first();
        // echo $project->meeting_id,' ';
        // die;

        return Project::whereUuid($uuid, 'uuid')->first();
    }

    public function clientproject()
    {
       return $this->hasOne('App\Models\ClientProject','id','client_id'); 
    }

}
