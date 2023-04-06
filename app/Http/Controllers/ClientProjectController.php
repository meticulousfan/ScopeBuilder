<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\ClientProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\Models\CollaborativeProjects;
use App\Models\Settings;
use App\Rules\CheckUserType;
use App\Models\User;
use App\Models\BbbMeeting;
use Illuminate\Support\Str;
use App\Models\AnonymousClientProject;
use App\Models\ClientProjectDetail;
use App\Models\PdfPermission;
use App\Models\ProjectQuestion;
use App\Models\Question;
use App\Models\Questionnaire;

class ClientProjectController extends Controller
{
    /**
     * Show form to edit the specified resource from storage.
     *
     * @param  \App\Models\ClientProject  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($project_uuid, $type, Request $request){
        $numberOfDevsAvailable=50;
        $callEnable = true;
        $pricePerCallMinute = 1;
        $extendDuration = true;
        if(Settings::first() != NULL) {
            $numberOfDevsAvailable = Settings::first()->numberOfDevsAvailable??'50';
            $callEnable = Settings::first()->calls;
            $pricePerCallMinute = Settings::first()->pricePerCallMinute;
            $extendDuration = Settings::first()->extendDuration;
        }

        $project = ClientProject::whereUuid($project_uuid,'uuid')->first();
        if($project->id!=$request->session()->get('current_project_id')){
            $request->session()->forget('formdata');
            $request->session()->forget('formloopdata');
        }
        $request->session()->put('current_project_id', $project->id);

        if( !empty($project->editing_user_id) && $project->editing_user_id != Auth::id() ){
            $user = User::where('id',$project->editing_user_id)->first();
            $request->session()->flash('error',$user->name.' is currently editing the form, any changes saved would cause their work to be lost');
        }else{
            $project->editing_user_id = Auth::id();
            $project->save();
        }

        $project_details = $project->getDetails($project->id);

        $mode_type = 'edit';

        $remainTime = BbbMeeting::select(
            DB::raw('SUM(IF(transactions.id IS NOT NULL,bbb_meetings.duration,0) - TIMESTAMPDIFF(MINUTE, bbb_meetings.start_time, IF(bbb_meetings.end_time IS NULL, bbb_meetings.start_time, bbb_meetings.end_time))-1)  AS remainTime')
        )
        ->join('transactions', 'transactions.bbb_meeting_id', '=', 'bbb_meetings.bbb_meeting_id', 'left')
        ->where('bbb_meetings.client_id', '=', Auth::id())
        ->orderBy('bbb_meetings.id', 'DESC')->first()->remainTime;

        return view($type.'.projects.create', compact('project', 'project_details', 'numberOfDevsAvailable','mode_type', 'callEnable', 'pricePerCallMinute','remainTime', 'extendDuration'));
    }

    public function getDevelopers(Request $request){
        $project = ClientProject::whereUuid($request->post('project_uuid'),'uuid')->first();
        $developers = User::select(
            'users.*',
            DB::raw('COALESCE((SELECT count(collaborative_projects.project_id) FROM collaborative_projects WHERE collaborative_projects.assigned_user_email = users.email AND collaborative_projects.project_id = '.$project->id.'),0) as collaborative_status'),
            DB::raw('COALESCE((SELECT ROUND(SUM(dev_call_ratings.stars)/COUNT(dev_call_ratings.stars)) FROM dev_call_ratings WHERE dev_call_ratings.dev_id = users.id),0) as rating')

        )->role('freelancer')->orderBy('collaborative_status', 'DESC')->get();
        return $developers;
    }
    public function edit_existing($project_uuid,$type, Request $request){
        $project = ClientProject::whereUuid($project_uuid,'uuid')->first();
        if($project->id!=$request->session()->get('current_project_id')){
            $request->session()->forget('formdata');
            $request->session()->forget('formloopdata');
        }
        $request->session()->put('current_project_id', $project->id);

        if( !empty($project->editing_user_id) && $project->editing_user_id != Auth::id() ){
            $user = User::where('id',$project->editing_user_id)->first();
            $request->session()->flash('error',$user->name.' is currently editing the form, any changes saved would cause their work to be lost');
        }else{
            $project->editing_user_id = Auth::id();
            $project->save();
        }

        $project_details = $project->getDetails($project->id);

        $mode_type = 'edit';
        if($type == 'client')
            return view('client.projects_existing.create', compact('project', 'project_details', 'mode_type'));
        else return view('developer.projects.create_existing', compact('project', 'project_details', 'mode_type'));
    }

    public function edit2($project_uuid, Request $request)
    {
        $project = ClientProject::whereUuid($project_uuid,'uuid')->first();
        if( !empty($project->editing_user_id) && $project->editing_user_id != Auth::id() ){
            $user = User::where('id',$project->editing_user_id)->first();
            $request->session()->flash('error',$user->name.' is currently editing the form, any changes saved would cause their work to be lost');
        }else{
            $project->editing_user_id = Auth::id();
            $project->save();
        }

        return view('client.projects.edit', compact('project'));
    }

    function formloopdata(ClientProject $project, Request $request){
        $formloopdata = $project->getDetails($project->id);
        $ia_arr = ['mobile', 'web', 'both'];
        if(!empty($request->session()->get('formloopdata'))){
            $formloopdatass = $request->session()->get('formloopdata');
            foreach($ia_arr as $ia){
                if(!empty($formloopdatass) && !empty($formloopdatass[$ia])){
                    if(!empty($formloopdata[$ia])){
                        $formloopdata[$ia] = array_merge($formloopdata[$ia], $formloopdatass[$ia]);
                    } else{
                        $formloopdata[$ia] = $formloopdatass[$ia];
                    }
                }
            }
        }
        return $formloopdata;
    }

    function formdata(ClientProject $project, Request $request){
        $formdata = $project->toArray();
        if(!empty($request->session()->get('formdata'))){
            $formdata1 = $request->session()->get('formdata');
            if(!empty($formdata1)){
                if(!empty($formdata)){
                    $formdata = array_merge($formdata, $formdata1);
                } else{
                    $formdata = $formdata1;
                }
            }
        }
        return $formdata;
    }

    public function update($project_uuid, Request $request){
        $project = ClientProject::whereUuid($project_uuid,'uuid')->first();
        $request_tech_type = $request->tech_type;
        if($request->left_step>2 && $request->left_step<7){
            $loop_data = array();
            if(!empty($request_tech_type) && !isset($loop_data[$request_tech_type])){
                $loop_data[$request_tech_type] = array();
            }
        }

        $project_details = $project->getDetails($project->id);
        $request->session()->put('formdata', $this->formdata($project, $request));
        $request->session()->put('formdata.left_step', $request->left_step);
        if(!empty($request->is_draft)){
            $request->session()->put('formdata.is_draft', $request->is_draft);

            $cr_project_num = 1;
            if($request->left_step==6){
                $cr_project_num = $request->project_number;
            }
            $request->session()->put('formdata.project_number', $cr_project_num);
        } else{
            $request->session()->put('formdata.is_draft', 0);
        }

        $request->session()->put('formloopdata', $this->formloopdata($project, $request));

        if($request->left_step==1){
            $request->validate([
              'name' => 'required',
              'description'=>'required',
              'mockup'=>'required',
              'example_projects'=>'required|array',
              'example_projects.*'=>'required|distinct',
              'mockup_url'=>'nullable',
              'mockup_file'=>'nullable',
            ],
            [
              'name.required' => 'The Project Name field is required',
              'description.required' => 'The Project Description field is required',
              'example_projects.required' => 'The Example Projects field is required',
              'example_projects.*.required' => 'The Example Projects field is required',
              'example_projects.*.distinct' => 'Duplicate entry not allowed',
            ]);
            $data = $request->except(['mockup_file','_token']);
            if($request->hasfile('mockup_file')){
                $fileName = $request->mockup_file->getClientOriginalName();
                $request->mockup_file->move(public_path('upload/mockup'), $fileName);
                $data['mockup'] = $fileName;
            }

            if(count($request->example_projects)){
                $data['example_projects'] = serialize($request->example_projects);
                $data['example_projects_count'] = count($request->example_projects);
            }

            /*if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $request->session()->put('formdata',$data);
            }*/
            $request->session()->put('formdata',$data);
        }elseif($request->left_step==2){
            if($request->is_draft==0){
                $validatedData = $request->validate(
                    [ 'type'=>'required' ],
                    [ 'type' => 'The Project types field is required' ]
                );
            }
            $data = $request->except('_token');

            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata', $data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata', array_merge($existingData, $data));
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200', 'step'=>'draft', 'url'=>route('client.projects')]);
            }
        }elseif($request->left_step==3){
            if($request->is_draft==0){
                $request->validate([
                    'user_types'=>'required|array',
                    'user_types.*'=>'required|distinct',
                    'number_of_user_types'=>'required'
                ], [
                    'user_types.required' => 'The User types field is required',
                    'user_types.*.required' => 'The User types field is required',
                    'user_types.*.distinct' => 'Please Remove Duplicate User Type',
                ]);
            }

            $loop_data[$request_tech_type] = $request->except('_token');

            if(count($request->user_types)){
                $loop_data[$request_tech_type]['user_types'] = serialize($request->user_types);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $loop_data[$request_tech_type]);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==4){
            if($request->is_draft==0){
                $validatedData = $request->validate([
                  'pages'=>'required|array',
                  'pages.*'=>'required|distinct',
                  'number_of_pages'=>'required'
                ],
                [
                  'pages.required' => 'The All Page Name field is required',
                  'pages.*.required' => 'The All Page Name field is required',
                  'pages.*.distinct' => 'Please Remove Duplicate Page',
                ]);
            }

            $loop_data[$request_tech_type] = $request->except('_token');
            if(count($request->pages)){
                $loop_data[$request_tech_type]['pages'] = serialize($request->pages);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $loop_data[$request_tech_type]);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }

            $stepThree = $request->session()->get('formloopdata');
            $permission = !empty($project_details[$request_tech_type]['pages_permission']) ? unserialize($project_details[$request_tech_type]['pages_permission']) : [];
            $permissionHtml = View::make('client.projects.user-permission',[
                'userTypes'=>unserialize($stepThree[$request_tech_type]['user_types']),
                'pages'=>$request->pages,
                'permission'=>$permission
            ])->render();

            return response()->json(
                [
                    'permissionHtml'=>$permissionHtml,
                    'status'=>'200',
                    'step'=>$request->left_step+1
                ]
            );
        }elseif($request->left_step==5){
            if($request->is_draft==0)
            {
                $stepFour = $request->session()->get('formloopdata');
                $rules['left_step']='required';
                $message['left_step.required'] ='Please select step';
                if(!empty($stepFour[$request_tech_type]['pages'])){
                    $pageList = unserialize($stepFour[$request_tech_type]['pages']);
                }else{
                    $pageList = unserialize($project_details[$request_tech_type]['pages']);
                }

                foreach ($pageList as $page) {
                    $name = str_replace(' ', '_',$page);
                    $rules[$name]='required|array';
                    $rules[$name.'.*']='required';

                    $message[$name.'.required'] ="The User Type field is required";
                    $message[$name.'.*.required'] ="The User Type field is required";
                }
                $validatedData = $request->validate($rules,$message);
            }

            $loop_data = $request->except(['_token','left_step','is_draft']);
            $customData['left_step'] = $request->left_step;
            $customData['is_draft'] = $request->is_draft;
            $customData['project_number'] = 1;
            if(count($loop_data)){
                $customData['pages_permission'] = serialize($loop_data);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $customData);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }

            $formdata = $request->session()->get('formdata');
            $formloopdata = $request->session()->get('formloopdata');
            if(!empty($formloopdata[$request_tech_type]['pages'])){
                $pageList = unserialize($formloopdata[$request_tech_type]['pages']);
            }else{
                $pageList = unserialize($project_details[$request_tech_type]['pages']);
            }
            $pagesInformation = [];
            if(!empty($formloopdata[$request_tech_type]['pages_information'])){
                $pagesInformation = unserialize($formloopdata[$request_tech_type]['pages_information']);
            }else if(!empty($project_details[$request_tech_type]['pages_information'])){
                $pagesInformation = unserialize($project_details[$request_tech_type]['pages_information']);
            }
            $infoHtml = View::make('client.projects.user-pageinfo',[
                'mode_type'=>'edit',
                'userTypes'=>$loop_data,
                'pages'=>$pageList,
                'project'=>$project,
                'pagesInformation'=>$pagesInformation
            ])->render();

            if(!empty($formdata['type'])){
                $type = $formdata['type'];
            }else{
                $type = $project->pages;
            }
            return response()->json(
                [
                    'infoHtml'=>$infoHtml,
                    'status'=>'200',
                    'project_type'=>$type,
                    'step'=>$request->left_step+1
            ]);
        }elseif($request->left_step==6){
            if($request->is_draft==0){
                $validatedData = $request->validate([
                   'page'=>'required|array',
                   'page.*.*.user.*'=>'required',
                   'action'=>'required|array',
                   'action.*.*.user.*'=>'required',
                    ],
                    [
                        'page.required'=>'This is required',
                        'page.*.*.user.*.required'=>'This is required',
                        'action.required'=>'This is required',
                        'action.*.*.user.*.required'=>'This is required',
                    ]
                );
            }

            $data = $request->except('_token');
            $pagesInformation=[];
            $customData['left_step'] = $request->left_step;
            $customData['is_draft'] = $request->is_draft;
            $customData['project_number'] = $request->project_number;
            if(count($data))
            {
                $formData = $request->session()->get('formdata');
                //$formLoopData = $request->session()->get('formloopdata');
                $formLoopData = $this->formloopdata($project, $request);
                $allPages = unserialize($formLoopData[$request_tech_type]['pages']);
                $userType = unserialize($formLoopData[$request_tech_type]['user_types']);
                foreach ($allPages as $page){
                    $name = str_replace(' ', '_',$page);
                    $userinfo = [];
                    foreach($userType as $user){
                        if(empty($data['page'][$name][$user])){
                            continue;
                        }
                        $pdata=[
                            'user'=>$user,
                            'information'=>$this->getText($data['page'][$name][$user]['user']),
                            'actions'=>$this->getText($data['action'][$name][$user]['user']),
                        ];
                        if(!empty($data['permockup'][$name][$user]['user'])){
                            $pdata['permockup'] =  $this->getText($data['permockup'][$name][$user]['user']);
                        }
                        $userinfo[] = $pdata;
                    }
                    $pagesInformation[]=[
                        'page'=>$page,
                        'users'=>$userinfo
                    ];

                }
                $customData['pages_information'] = serialize($pagesInformation);
            }
            # echo '<pre>'; print_r($pagesInformation)  ; die;

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$customData);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $customData);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==7){
            if($request->is_draft==0){
                $request->validate([
                'budget' =>'required',
                  'mobile_frameworks'=>"required_if:project_type,mobile,both",
                  'web_frameworks'=>"required_if:project_type,web,both",
                ],
                [
                    'mobile_frameworks.required' => 'The Mobile Frameworks field is required',
                    'web_frameworks.required' => 'The Web Frameworks field is required',
                ]);
            }
            $data = $request->except('_token');
            $data['project_number'] = 1;
            $data['budget'] = $request->budget;
            $data['contact_developer'] = $request->contact_developer;

            if(!empty($data['web_frameworks']) && count($data['web_frameworks'])){
                $data['web_frameworks'] = serialize($data['web_frameworks']);
            }
            if(!empty($data['mobile_frameworks']) && count($data['mobile_frameworks']) ){
                $data['mobile_frameworks'] = serialize($data['mobile_frameworks']);
            }
            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$data));
            }
            $this->saveStepData($request,$project);
            // save for later
            if($request->is_draft==1){
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }else{
                $request->session()->flash('success','Project has been updated Successfully');
                return response()->json(['status'=>'200','step'=>'submited','url'=>route('client.projects')]);
            }

        }

        try
        {
            $lefted_steps = $request->left_step;
            if($lefted_steps==6){
                $crformdata = $request->session()->get('formdata');

                if(!empty($crformdata['type'])) $prj_type = $crformdata['type'];
                else $prj_type = $project->type;

                if(!empty($crformdata['both_same_functionality'])) $sm_functionality = $crformdata['both_same_functionality'];
                else $sm_functionality = $project->both_same_functionality;

                $tc_type = $request_tech_type;
                $goto_back = 0;

                if($prj_type=="both" && $sm_functionality=="0" && $tc_type=="mobile"){
                    $lefted_steps = 2;
                    $goto_back = 1;
                }

                return response()->json(['status'=>'200','step'=>$lefted_steps+1, 'project_type'=>$prj_type, 'same_functionality'=>$sm_functionality, 'tech_type'=>$tc_type, 'goto_back'=>$goto_back]);
            }

            return response()->json(['status'=>'200','step'=>$request->left_step+1]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status'=>'422','message'=>$e->getMessage().' '.$e->getFile().' '.$e->getLine()]);
        }
    }

    public function update_existing($project_uuid, Request $request){
        $project = ClientProject::whereUuid($project_uuid,'uuid');
        $request_tech_type = $request->tech_type;
        if($request->left_step>2 && $request->left_step<7){
            $loop_data = array();
            if(!empty($request_tech_type) && !isset($loop_data[$request_tech_type])){
                $loop_data[$request_tech_type] = array();
            }
        }

        $project_details = $project->getDetails($project->id);
        $request->session()->put('formdata', $this->formdata($project, $request));
        $request->session()->put('formdata.left_step', $request->left_step);
        if(!empty($request->is_draft)){
            $request->session()->put('formdata.is_draft', $request->is_draft);

            $cr_project_num = 1;
            if($request->left_step==6){
                $cr_project_num = $request->project_number;
            }
            $request->session()->put('formdata.project_number', $cr_project_num);
        } else{
            $request->session()->put('formdata.is_draft', 0);
        }
        $request->session()->put('formloopdata', $this->formloopdata($project, $request));

        if($request->left_step==1){
                $request->validate([
                  'name' => 'required',
                  'description'=>'required',
                  'code_repository_link'=>'required',
                  'mockup'=>'required',
                  'example_projects'=>'required|array',
                  'example_projects.*'=>'required|distinct',
                  'mockup_url'=>'nullable',
                  'mockup_file'=>'nullable',
                ],
                [
                  'name.required' => 'The Project Name field is required',
                  'description.required' => 'The Project Description field is required',
                  'code_repository_link.required' => 'Code repository link field is required',
                  'example_projects.required' => 'The Example Projects field is required',
                  'example_projects.*.required' => 'The Example Projects field is required',
                  'example_projects.*.distinct' => 'Duplicate entry not allowed',
                ]);
                $data = $request->except([/*'mockup_file',*/'_token']);
                if($request->hasfile('mockup_file'))
                {
                    $fileName = $request->mockup_file->getClientOriginalName();
                    $request->mockup_file->move(public_path('upload/mockup'), $fileName);
                    $data['mockup'] = $fileName;
                }
                if(count($request->example_projects)){
                    $data['example_projects'] = serialize($request->example_projects);
                    $data['example_projects_count'] = count($request->example_projects);
                }

                /*if(empty($request->session()->get('formdata'))){
                    $request->session()->put('formdata',$data);
                }else{
                    $request->session()->put('formdata',$data);
                }*/
                $request->session()->put('formdata',$data);
        }elseif($request->left_step==2){
           if($request->is_draft==0){
                $validatedData = $request->validate([
                  'type'=>'required',
                  'where_add'=>'required',
                ],
                [
                  'type' => 'The Project types field is required',
                  'where_add' => 'Where are we adding field is required',
                ]);
            }
            $data = $request->except('_token');

            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$data));
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==3){
            if($request->is_draft==0){
                $request->validate([
                    'user_types'=>'required|array',
                    'user_types.*'=>'required|distinct',
                    'number_of_user_types'=>'required'
                ], [
                    'user_types.required' => 'The User types field is required',
                    'user_types.*.required' => 'The User types field is required',
                    'user_types.*.distinct' => 'Please Remove Duplicate User Type',
                ]);
            }

            $loop_data[$request_tech_type] = $request->except('_token');

            if(count($request->user_types)){
                $loop_data[$request_tech_type]['user_types'] = serialize($request->user_types);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $loop_data[$request_tech_type]);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==4){
            if($request->is_draft==0)
            {
                $validatedData = $request->validate([
                  'pages'=>'required|array',
                  'pages.*'=>'required|distinct',
                  'number_of_pages'=>'required'
                ],
                [
                  'pages.required' => 'The All Page Name field is required',
                  'pages.*.required' => 'The All Page Name field is required',
                  'pages.*.distinct' => 'Please Remove Duplicate Page',
                ]);
            }

            $loop_data[$request_tech_type] = $request->except('_token');
            if(count($request->pages)){
                $loop_data[$request_tech_type]['pages'] = serialize($request->pages);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $loop_data[$request_tech_type]);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }

            $stepThree = $request->session()->get('formloopdata');
            $permission = !empty($project_details[$request_tech_type]['pages_permission']) ? unserialize($project_details[$request_tech_type]['pages_permission']) : [];
            $permissionHtml = View::make('client.projects.user-permission',[
                'userTypes'=>unserialize($stepThree[$request_tech_type]['user_types']),
                'pages'=>$request->pages,
                'permission'=>$permission
            ])->render();

            return response()->json(
                [
                    'permissionHtml'=>$permissionHtml,
                    'status'=>'200',
                    'step'=>$request->left_step+1
            ]);
        }elseif($request->left_step==5){
            if($request->is_draft==0)
            {
                $stepFour = $request->session()->get('formloopdata');
                $rules['left_step']='required';
                $message['left_step.required'] ='Please select step';
                if(!empty($stepFour[$request_tech_type]['pages'])){
                    $pageList = unserialize($stepFour[$request_tech_type]['pages']);
                }else{
                    $pageList = unserialize($project_details[$request_tech_type]['pages']);
                }

                foreach ($pageList as $page) {
                    $name = str_replace(' ', '_',$page);
                    $rules[$name]='required|array';
                    $rules[$name.'.*']='required';

                    $message[$name.'.required'] ="The User Type field is required";
                    $message[$name.'.*.required'] ="The User Type field is required";
                }
                $validatedData = $request->validate($rules,$message);
            }

            $loop_data = $request->except(['_token','left_step','is_draft']);
            $customData['left_step'] = $request->left_step;
            $customData['is_draft'] = $request->is_draft;
            $customData['project_number'] = 1;
            if(count($loop_data)){
                $customData['pages_permission'] = serialize($loop_data);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $customData);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }

            $formdata = $request->session()->get('formdata');
            $formloopdata = $request->session()->get('formloopdata');
            if(!empty($formloopdata[$request_tech_type]['pages'])){
                $pageList = unserialize($formloopdata[$request_tech_type]['pages']);
            }else{
                $pageList = unserialize($project_details[$request_tech_type]['pages']);
            }
            $pagesInformation = [];
            if(!empty($formloopdata[$request_tech_type]['pages_information'])){
                $pagesInformation = unserialize($formloopdata[$request_tech_type]['pages_information']);
            }else if(!empty($project_details[$request_tech_type]['pages_information'])){
                $pagesInformation = unserialize($project_details[$request_tech_type]['pages_information']);
            }
            $infoHtml = View::make('client.projects.user-pageinfo',[
                'mode_type'=>'edit',
                'userTypes'=>$loop_data,
                'pages'=>$pageList,
                'project'=>$project,
                'pagesInformation'=>$pagesInformation
            ])->render();

            if(!empty($formdata['type'])){
                $type = $formdata['type'];
            }else{
                $type = $project->pages;
            }
            return response()->json(
                [
                    'infoHtml'=>$infoHtml,
                    'status'=>'200',
                    'project_type'=>$type,
                    'step'=>$request->left_step+1
            ]);
        }elseif($request->left_step==6){
            if($request->is_draft==0){
                $validatedData = $request->validate([
                   'page'=>'required|array',
                   'page.*.*.user.*'=>'required',
                   'action'=>'required|array',
                   'action.*.*.user.*'=>'required',
                    ],
                    [
                        'page.required'=>'This is required',
                        'page.*.*.user.*.required'=>'This is required',
                        'action.required'=>'This is required',
                        'action.*.*.user.*.required'=>'This is required',
                    ]
                );
            }

            $data = $request->except('_token');
            $pagesInformation=[];
            $customData['left_step'] = $request->left_step;
            $customData['is_draft'] = $request->is_draft;
            $customData['project_number'] = $request->project_number;
            if(count($data))
            {
                $formData = $request->session()->get('formdata');
                #$formLoopData = $request->session()->get('formloopdata');
                $formLoopData = $this->formloopdata($project, $request);
                $allPages = unserialize($formLoopData[$request_tech_type]['pages']);
                $userType = unserialize($formLoopData[$request_tech_type]['user_types']);
                foreach ($allPages as $page){
                    $name = str_replace(' ', '_',$page);
                    $userinfo = [];
                    foreach($userType as $user){
                        if(empty($data['page'][$name][$user])){
                            continue;
                        }
                        $pdata=[
                            'user'=>$user,
                            'information'=>$this->getText($data['page'][$name][$user]['user']),
                            'actions'=>$this->getText($data['action'][$name][$user]['user']),
                        ];
                        if(!empty($data['permockup'][$name][$user]['user'])){
                            $pdata['permockup'] =  $this->getText($data['permockup'][$name][$user]['user']);
                        }
                        $userinfo[] = $pdata;
                    }
                    $pagesInformation[]=[
                        'page'=>$page,
                        'users'=>$userinfo
                    ];

                }
                $customData['pages_information'] = serialize($pagesInformation);
            }
            # echo '<pre>'; print_r($pagesInformation)  ; die;

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$customData);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $customData);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request, $project);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==7){
            if($request->is_draft==0){
                $request->validate([
                'budget' =>'required',
                  'mobile_frameworks'=>"required_if:project_type,mobile,both",
                  'web_frameworks'=>"required_if:project_type,web,both",
                ],
                [
                    'mobile_frameworks.required' => 'The Mobile Frameworks field is required',
                    'web_frameworks.required' => 'The Web Frameworks field is required',
                ]);
            }
            $data = $request->except('_token');
            $data['project_number'] = 1;
            $data['budget'] = $request->budget;
            $data['contact_developer'] = $request->contact_developer;

            if(!empty($data['web_frameworks']) && count($data['web_frameworks'])){
                $data['web_frameworks'] = serialize($data['web_frameworks']);
            }
            if(!empty($data['mobile_frameworks']) && count($data['mobile_frameworks']) ){
                $data['mobile_frameworks'] = serialize($data['mobile_frameworks']);
            }
            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$data));
            }
            $this->saveStepData($request,$project);
            // save for later
            if($request->is_draft==1){
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }else{
                $request->session()->flash('success','Project has been updated Successfully');
                return response()->json(['status'=>'200','step'=>'submited','url'=>route('client.projects')]);
            }

        }

        try
        {
            $lefted_steps = $request->left_step;
            if($lefted_steps==6){
                $crformdata = $request->session()->get('formdata');

                if(!empty($crformdata['type'])) $prj_type = $crformdata['type'];
                else $prj_type = $project->type;

                if(!empty($crformdata['both_same_functionality'])) $sm_functionality = $crformdata['both_same_functionality'];
                else $sm_functionality = $project->both_same_functionality;

                $tc_type = $request_tech_type;
                $goto_back = 0;

                if($prj_type=="both" && $sm_functionality=="0" && $tc_type=="mobile"){
                    $lefted_steps = 2;
                    $goto_back = 1;
                }

                return response()->json(['status'=>'200','step'=>$lefted_steps+1, 'project_type'=>$prj_type, 'same_functionality'=>$sm_functionality, 'tech_type'=>$tc_type, 'goto_back'=>$goto_back]);
            }

            return response()->json(['status'=>'200','step'=>$request->left_step+1]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status'=>'422','message'=>$e->getMessage().' '.$e->getFile().' '.$e->getLine()]);
        }
    }

    /**
     * Show form to edit the specified resource from storage.
     *
     * @param  \App\Models\ClientProject  $project
     * @return \Illuminate\Http\Response
     */
    public function getData(ClientProject $project)
    {
        $pageInformation = unserialize($project->pages_information);
        return response()->json([
            'example_projects'  => unserialize($project->example_projects),
            'user_types'        => unserialize($project->user_types),
            'pages'             => unserialize($project->pages),
            'pages_information' => $pageInformation,
            'web_frameworks'    => unserialize($project->web_frameworks),
            'mobile_frameworks' => unserialize($project->mobile_frameworks),
            'step'              => $left_step > 0 ? $left_step : 1,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request_tech_type = $request->tech_type;
        if($request->left_step>2 && $request->left_step<7){
            $loop_data = array();
            if(!empty($request_tech_type) && !isset($loop_data[$request_tech_type])){
                $loop_data[$request_tech_type] = array();
            }
        }
        if($request->left_step==1){
            $request->validate([
              'name' => 'required',
              'description'=>'required',
              'mockup'=>'required',
              'example_projects'=>'required|array',
              'example_projects.*'=>'required|distinct',
              'mockup_url'=>'nullable',
              'mockup_file'=>'nullable',
            ],
            [
              'name.required' => 'The Project Name field is required',
              'description.required' => 'The Project Description field is required',
              'example_projects.required' => 'The Example Projects field is required',
              'example_projects.*.required' => 'The Example Projects field is required',
              'example_projects.*.distinct' => 'Duplicate entry not allowed',
            ]);
            $data = $request->except(['mockup_file','_token']);
            if($request->hasfile('mockup_file'))
            {
                $fileName = $request->mockup_file->getClientOriginalName();
                $request->mockup_file->move(public_path('upload/mockup'), $fileName);
                $data['mockup'] = $fileName;
            }
            if(count($request->example_projects)){
                $data['example_projects'] = serialize($request->example_projects);
                $data['example_projects_count'] = count($request->example_projects);
            }

            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $request->session()->put('formdata',$data);
            }

            if(auth()->check()) {
                $project_uuid = $this->saveStepData($request);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects.edit', [$project_uuid,'client'])]);
            }
        }elseif($request->left_step==2){
           if($request->is_draft==0){
                $validatedData = $request->validate([
                  'type'=>'required',
                ],
                [
                  'type' => 'The Project types field is required',
                ]);
            }
            $data = $request->except('_token');
            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$data));
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==3){
            if($request->is_draft==0){
                $request->validate(
                    [
                        'user_types'=>'required|array',
                        'user_types.*'=>'required|distinct',
                        'number_of_user_types'=>'required'
                    ], [
                        'user_types.required' => 'The User types field is required',
                        'user_types.*.required' => 'The User types field is required',
                        'user_types.*.distinct' => 'Please Remove Duplicate User Type',
                    ]
                );
            }

            $loop_data[$request_tech_type] = $request->except('_token');
            if(count($request->user_types)){
                $loop_data[$request_tech_type]['user_types'] = serialize($request->user_types);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $loop_data[$request_tech_type]);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }
            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==4){
            if($request->is_draft==0){
                $validatedData = $request->validate([
                  'pages'=>'required|array',
                  'pages.*'=>'required|distinct',
                  'number_of_pages'=>'required'
                ],
                [
                  'pages.required' => 'The All Page Name field is required',
                  'pages.*.required' => 'The All Page Name field is required',
                  'pages.*.distinct' => 'Please Remove Duplicate Page',
                ]);
            }

            $loop_data[$request_tech_type] = $request->except('_token');
            if(count($request->pages)){
                $loop_data[$request_tech_type]['pages'] = serialize($request->pages);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $loop_data[$request_tech_type]);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200', 'step'=>'draft', 'url'=>route('client.projects')]);
            }

            $stepThree = $request->session()->get('formloopdata');
            $permission = !empty($stepThree[$request_tech_type]['pages_permission']) ? unserialize($stepThree[$request_tech_type]['pages_permission']) : [];
            $permissionHtml = View::make('client.projects.user-permission',[
                'userTypes'=>unserialize($stepThree[$request_tech_type]['user_types']),
                'pages'=>$request->pages,
                'permission'=>$permission
            ])->render();

            return response()->json(
                [
                    'permissionHtml'=>$permissionHtml,
                    'status'=>'200',
                    'step'=>$request->left_step+1
                ]
            );
        }elseif($request->left_step==5){
            if($request->is_draft==0){
                $stepFour = $request->session()->get('formloopdata');
                $rules['left_step'] = 'required';
                $message['left_step.required'] ='Please select step';
                $pageList = unserialize($stepFour[$request_tech_type]['pages']);
                foreach ($pageList as $page) {
                    $name = str_replace(' ', '_', $page);
                    $rules[$name]='required|array';
                    $rules[$name.'.*']='required';

                    $message[$name.'.required'] = "The User Type field is required";
                    $message[$name.'.*.required'] = "The User Type field is required";
                }
                $validatedData = $request->validate($rules,$message);
            }

            $loop_data = $request->except(['_token', 'left_step', 'is_draft']);
            $customData['left_step'] = $request->left_step;
            $customData['is_draft'] = $request->is_draft;
            $customData['project_number'] = 1;
            if(count($loop_data)){
                $customData['pages_permission'] = serialize($loop_data);
            }
            /*if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$customData));
            }*/

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $customData);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200', 'step'=>'draft', 'url'=>route('client.projects')]);
            }

            $formdata = $request->session()->get('formdata');
            $formloopdata = $request->session()->get('formloopdata');
            $pagesInformation = [];
            if(!empty($formloopdata[$request_tech_type]['pages_information'])){
                $pagesInformation = unserialize($formloopdata[$request_tech_type]['pages_information']);
            }
            $infoHtml = View::make('client.projects.user-pageinfo',[
                'mode_type'=>'create',
                'userTypes'=>$loop_data,
                'pages'=>unserialize($formloopdata[$request_tech_type]['pages']),
                'pagesInformation'=>$pagesInformation
            ])->render();

            return response()->json(
                [
                    'infoHtml'=>$infoHtml,
                    'status'=>'200',
                    'project_type'=>$formdata['type'],
                    'step'=>$request->left_step+1
            ]);
        }elseif($request->left_step==6){
            if($request->is_draft==0){
                $validatedData = $request->validate([
                   'page'=>'required|array',
                   'page.*.*.user.*'=>'required',
                   'action'=>'required|array',
                   'action.*.*.user.*'=>'required',
                    ],
                    [
                        'page.required'=>'This is required',
                        'page.*.*.user.*.required'=>'This is required',
                        'action.required'=>'This is required',
                        'action.*.*.user.*.required'=>'This is required',
                    ]
                );
            }
            $data = $request->except('_token');
            $pagesInformation=[];
            $customData['left_step'] = $request->left_step;
            $customData['is_draft'] = $request->is_draft;
            $customData['project_number'] = $request->project_number;
            if(count($data))
            {
                $formData = $request->session()->get('formdata');
                $formLoopData = $request->session()->get('formloopdata');
                $allPages = unserialize($formLoopData[$request_tech_type]['pages']);
                $userType = unserialize($formLoopData[$request_tech_type]['user_types']);
                foreach ($allPages as $page) {
                    $name = str_replace(' ', '_',$page);
                    $userinfo = [];
                    foreach($userType as $user){
                        if(empty($data['page'][$name][$user])){
                            continue;
                        }

                        $pdata=[
                            'user'=>$user,
                            'information'=>$this->getText($data['page'][$name][$user]['user']),
                            'actions'=>$this->getText($data['action'][$name][$user]['user']),
                        ];
                        if(!empty($data['permockup'][$name][$user]['user'])){
                            $pdata['permockup'] =  $this->getText($data['permockup'][$name][$user]['user']);
                        }

                        $userinfo[]=$pdata;
                    }
                    $pagesInformation[]=[
                        'page'=>$page,
                        'users'=>$userinfo
                    ];

                }
                $customData['pages_information'] = serialize($pagesInformation);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$customData);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $customData);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==7){
            if($request->is_draft==0){
                $validatedData = $request->validate([
                    'budget' =>'required',
                    'mobile_frameworks'=>"required_if:project_type,mobile,both",
                    'web_frameworks'=>"required_if:project_type,web,both",
                ],
                [
                    'mobile_frameworks.required' => 'The Mobile Frameworks field is required',
                    'web_frameworks.required' => 'The Web Frameworks field is required',
                ]);
            }
            $data = $request->except('_token');
            $data['project_number'] = 1;
            $data['budget'] = $request->budget;
            $data['contact_developer'] = $request->contact_developer;

            if(!empty($data['web_frameworks']) && count($data['web_frameworks'])){
                $data['web_frameworks'] = serialize($data['web_frameworks']);
            }
            if(!empty($data['mobile_frameworks']) && count($data['mobile_frameworks']) ){
                $data['mobile_frameworks'] = serialize($data['mobile_frameworks']);
            }
            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$data));
            }
            $project_uuid = $this->saveStepData($request);
            // save for later
            if($request->is_draft==1){
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }else{

                $request->session()->flash('success','Project has been created Successfully');

                if(!auth()->check()) {

                    $redirect_url = route('client.register.form');

                    $redirect_url .= '?anonymous_project=' . $project_uuid;

                    if(!empty($request->get('ref'))) {
                        $redirect_url .= '&ref=' . $request->get('ref');
                    }
                }
                else {
                    $redirect_url = route('client.projects');
                }

                return response()->json([
                    'status'=>'200',
                    'step'=>'submited',
                    'url' => $redirect_url
                ]);
            }
        }

        try
        {
            $lefted_steps = $request->left_step;
            if($lefted_steps==6){
                $crformdata = $request->session()->get('formdata');
                $prj_type = $crformdata['type'];
                $sm_functionality = $crformdata['both_same_functionality'];
                $tc_type = $request_tech_type;
                $goto_back = 0;

                if($prj_type=="both" && $sm_functionality=="0" && $tc_type=="mobile"){
                    $lefted_steps = 2;
                    $goto_back = 1;
                }

                return response()->json(['status'=>'200','step'=>$lefted_steps+1, 'project_type'=>$prj_type, 'same_functionality'=>$sm_functionality, 'tech_type'=>$tc_type, 'goto_back'=>$goto_back]);
            }
            return response()->json(['status'=>'200','step'=>$request->left_step+1]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status'=>'422','message'=>$e->getMessage().' '.$e->getFile().' '.$e->getLine()]);
        }
    }

    public function storeNew(Request $request) {
        $request->validate([
            'project_name' => 'required',
            'project_type'=>'required',
          ]
        );
        $formData = [
            'user_id' => auth()->user()->id,
            'name' => $request->project_name,
            'type_id' => $request->project_type,
            'skills' => json_encode($request->skills),
            'description' => '',
            'example_projects' => '',
            'example_projects_count' => 0,
            'left_step' => 0,
        ];
        $project = ClientProject::create($formData);

        $questionnaire = Questionnaire::with('questions')->where('type_id', $project->type_id)->first();
        if($questionnaire) {
            $questions = Question::select('description', 'hint', 'step', 'order', 'mandatory', 'price', 'status', 'fields', 'position')->where(['questionnaire_id' => $questionnaire->id])->get()->toArray();
            foreach($questions as $question) {
                $question['project_id'] = $project->id;
                ProjectQuestion::create($question);
            }
        }

        return redirect()->route('client.projects');
    }

    public function storeAnonymous(Request $request) {
        $request->validate([
            'name' => 'required',
            'sub_type_id'=>'required',
          ]
        );

        $formData = [
            'user_id' => 0,
            'anonymous_user_id' => $request->anonymous_id,
            'name' => $request->name,
            'type_id' => $request->sub_type_id,
            'skills' => json_encode($request->skills_ids),
            'description' => '',
            'example_projects' => '',
            'example_projects_count' => 0,
            'left_step' => 0,
        ];

        $project = $this->createProject($formData);

        return response()->json(['success' => 1, 'project' => $project], 200);
    }

    public function createProject($formData) {
        $project = ClientProject::create($formData);

        $questionnaire = Questionnaire::with('questions')->where('type_id', $project->type_id)->first();
        if($questionnaire) {
            $questions = Question::select('description', 'hint', 'step', 'order', 'mandatory', 'price', 'status', 'fields', 'position')->where(['questionnaire_id' => $questionnaire->id])->get()->toArray();
            foreach($questions as $question) {
                $question['project_id'] = $project->id;
                ProjectQuestion::create($question);
            }
        }

        return $project;
    }

    public function projectQuestions(Request $request, $id)
    {
        $perpage = 10;
        $skills = [];
        $skillsNum = 0;
        $menu = 'questions-new';
        return view('client.projects.new.create', compact('skills', 'menu',  'skillsNum', 'perpage', 'id'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }

    public function store_existing(Request $request){
        $request_tech_type = $request->tech_type;
        if($request->left_step>2 && $request->left_step<7){
            $loop_data = array();
            if(!empty($request_tech_type) && !isset($loop_data[$request_tech_type])){
                $loop_data[$request_tech_type] = array();
            }
        }

        if($request->left_step==1){
            $request->validate([
              'name' => 'required',
              'description'=>'required',
              'code_repository_link'=>'required',
              'mockup'=>'required',
              'example_projects'=>'required|array',
              'example_projects.*'=>'required|distinct',
              'mockup_url'=>'nullable',
              'mockup_file'=>'nullable',
            ],
            [
              'name.required' => 'The Project Name field is required',
              'description.required' => 'The Project Description field is required',
              'code_repository_link.required' => 'Code repository link field is required',
              'example_projects.required' => 'The Example Projects field is required',
              'example_projects.*.required' => 'The Example Projects field is required',
              'example_projects.*.distinct' => 'Duplicate entry not allowed',
            ]);
            $data = $request->except(['mockup_file','_token']);
            if($request->hasfile('mockup_file'))
            {
                $fileName = $request->mockup_file->getClientOriginalName();
                $request->mockup_file->move(public_path('upload/mockup'), $fileName);
                $data['mockup'] = $fileName;
            }
            if(count($request->example_projects)){
                $data['example_projects'] = serialize($request->example_projects);
                $data['example_projects_count'] = count($request->example_projects);
            }

            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $request->session()->put('formdata',$data);
            }
        }elseif($request->left_step==2){
           if($request->is_draft==0){
                $validatedData = $request->validate([
                  'type'=>'required',
                  'where_add'=>'required',
                ],
                [
                  'type' => 'The Project types field is required',
                  'where_add' => 'Where are we adding field is required',
                ]);
            }
            $data = $request->except('_token');
            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$data));
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==3){
            if($request->is_draft==0){
                $request->validate(
                    [
                        'user_types'=>'required|array',
                        'user_types.*'=>'required|distinct',
                        'number_of_user_types'=>'required'
                    ], [
                        'user_types.required' => 'The User types field is required',
                        'user_types.*.required' => 'The User types field is required',
                        'user_types.*.distinct' => 'Please Remove Duplicate User Type',
                    ]
                );
            }

            $loop_data[$request_tech_type] = $request->except('_token');
            if(count($request->user_types)){
                $loop_data[$request_tech_type]['user_types'] = serialize($request->user_types);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $loop_data[$request_tech_type]);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }
            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==4){
            if($request->is_draft==0)
            {
                $validatedData = $request->validate([
                  'pages'=>'required|array',
                  'pages.*'=>'required|distinct',
                  'number_of_pages'=>'required'
                ],
                [
                  'pages.required' => 'The All Page Name field is required',
                  'pages.*.required' => 'The All Page Name field is required',
                  'pages.*.distinct' => 'Please Remove Duplicate Page',
                ]);
            }
            $loop_data[$request_tech_type] = $request->except('_token');
            if(count($request->pages)){
                $loop_data[$request_tech_type]['pages'] = serialize($request->pages);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $loop_data[$request_tech_type]);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200', 'step'=>'draft', 'url'=>route('client.projects')]);
            }

            $stepThree = $request->session()->get('formloopdata');
            $permission = !empty($stepThree[$request_tech_type]['pages_permission']) ? unserialize($stepThree[$request_tech_type]['pages_permission']) : [];
            $permissionHtml = View::make('client.projects.user-permission',[
                'userTypes'=>unserialize($stepThree[$request_tech_type]['user_types']),
                'pages'=>$request->pages,
                'permission'=>$permission
            ])->render();

            return response()->json(
                [
                    'permissionHtml'=>$permissionHtml,
                    'status'=>'200',
                    'step'=>$request->left_step+1
                ]
            );
        }elseif($request->left_step==5){
            if($request->is_draft==0){
                $stepFour = $request->session()->get('formloopdata');
                $rules['left_step'] = 'required';
                $message['left_step.required'] ='Please select step';
                $pageList = unserialize($stepFour[$request_tech_type]['pages']);
                foreach ($pageList as $page) {
                    $name = str_replace(' ', '_', $page);
                    $rules[$name]='required|array';
                    $rules[$name.'.*']='required';

                    $message[$name.'.required'] = "The User Type field is required";
                    $message[$name.'.*.required'] = "The User Type field is required";
                }
                $validatedData = $request->validate($rules,$message);
            }

            $loop_data = $request->except(['_token', 'left_step', 'is_draft']);
            $customData['left_step'] = $request->left_step;
            $customData['is_draft'] = $request->is_draft;
            $customData['project_number'] = 1;
            if(count($loop_data)){
                $customData['pages_permission'] = serialize($loop_data);
            }
            /*if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$customData));
            }*/

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$loop_data);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $customData);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200', 'step'=>'draft', 'url'=>route('client.projects')]);
            }

            $formdata = $request->session()->get('formdata');
            $formloopdata = $request->session()->get('formloopdata');
            $pagesInformation = [];
            if(!empty($formloopdata[$request_tech_type]['pages_information'])){
                $pagesInformation = unserialize($formloopdata[$request_tech_type]['pages_information']);
            }
            $infoHtml = View::make('client.projects.user-pageinfo',[
                'mode_type'=>'create',
                'userTypes'=>$loop_data,
                'pages'=>unserialize($formloopdata[$request_tech_type]['pages']),
                'pagesInformation'=>$pagesInformation
            ])->render();

            return response()->json(
                [
                    'infoHtml'=>$infoHtml,
                    'status'=>'200',
                    'project_type'=>$formdata['type'],
                    'step'=>$request->left_step+1
            ]);
        }elseif($request->left_step==6){
            if($request->is_draft==0){
                $validatedData = $request->validate([
                   'page'=>'required|array',
                   'page.*.*.user.*'=>'required',
                   'action'=>'required|array',
                   'action.*.*.user.*'=>'required',
                    ],
                    [
                        'page.required'=>'This is required',
                        'page.*.*.user.*.required'=>'This is required',
                        'action.required'=>'This is required',
                        'action.*.*.user.*.required'=>'This is required',
                    ]
                );
            }
            $data = $request->except('_token');
            $pagesInformation=[];
            $customData['left_step'] = $request->left_step;
            $customData['is_draft'] = $request->is_draft;
            $customData['project_number'] = $request->project_number;
            if(count($data))
            {
                $formData = $request->session()->get('formdata');
                $formLoopData = $request->session()->get('formloopdata');
                $allPages = unserialize($formLoopData[$request_tech_type]['pages']);
                $userType = unserialize($formLoopData[$request_tech_type]['user_types']);
                foreach ($allPages as $page) {
                    $name = str_replace(' ', '_',$page);
                    $userinfo = [];
                    foreach($userType as $user){
                        if(empty($data['page'][$name][$user])){
                            continue;
                        }

                        $pdata=[
                            'user'=>$user,
                            'information'=>$this->getText($data['page'][$name][$user]['user']),
                            'actions'=>$this->getText($data['action'][$name][$user]['user']),
                        ];
                        if(!empty($data['permockup'][$name][$user]['user'])){
                            $pdata['permockup'] =  $this->getText($data['permockup'][$name][$user]['user']);
                        }

                        $userinfo[]=$pdata;
                    }
                    $pagesInformation[]=[
                        'page'=>$page,
                        'users'=>$userinfo
                    ];

                }
                $customData['pages_information'] = serialize($pagesInformation);
            }

            if(empty($request->session()->get('formloopdata')) && empty($request->session()->get('formloopdata')[$request_tech_type])){
                $request->session()->put('formloopdata',$customData);
            }else{
                $existingData = $request->session()->get('formloopdata');
                if(empty($existingData[$request_tech_type])) $existingData[$request_tech_type] = array();
                $result_array = array_merge($existingData[$request_tech_type], $customData);
                #$request->session()->put('formloopdata', array( $request_tech_type => $result_array));
                $request->session()->put('formloopdata.'.$request_tech_type, $result_array);
            }

            // save for later
            if($request->is_draft==1){
                $this->saveStepData($request);
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }
        }elseif($request->left_step==7){
            if($request->is_draft==0){
                $validatedData = $request->validate([
                    'budget' =>'required',
                  'mobile_frameworks'=>"required_if:project_type,mobile,both",
                  'web_frameworks'=>"required_if:project_type,web,both",
                ],
                [
                    'mobile_frameworks.required' => 'The Mobile Frameworks field is required',
                    'web_frameworks.required' => 'The Web Frameworks field is required',
                ]);
            }
            $data = $request->except('_token');
            $data['project_number'] = 1;
            $data['budget'] = $request->budget;
            $data['contact_developer'] = $request->contact_developer;

            if(!empty($data['web_frameworks']) && count($data['web_frameworks'])){
                $data['web_frameworks'] = serialize($data['web_frameworks']);
            }
            if(!empty($data['mobile_frameworks']) && count($data['mobile_frameworks']) ){
                $data['mobile_frameworks'] = serialize($data['mobile_frameworks']);
            }
            if(empty($request->session()->get('formdata'))){
                $request->session()->put('formdata',$data);
            }else{
                $existingData = $request->session()->get('formdata');
                $request->session()->put('formdata',array_merge($existingData,$data));
            }
            $this->saveStepData($request);
            // save for later
            if($request->is_draft==1){
                return response()->json(['status'=>'200','step'=>'draft','url'=>route('client.projects')]);
            }else{
                $request->session()->flash('success','Project has been created Successfully');
                return response()->json(['status'=>'200','step'=>'submited','url'=>route('client.projects')]);
            }

        }

        try
        {
            $lefted_steps = $request->left_step;
            if($lefted_steps==6){
                $crformdata = $request->session()->get('formdata');
                $prj_type = $crformdata['type'];
                $sm_functionality = $crformdata['both_same_functionality'];
                $tc_type = $request_tech_type;
                $goto_back = 0;

                if($prj_type=="both" && $sm_functionality=="0" && $tc_type=="mobile"){
                    $lefted_steps = 2;
                    $goto_back = 1;
                }

                return response()->json(['status'=>'200','step'=>$lefted_steps+1, 'project_type'=>$prj_type, 'same_functionality'=>$sm_functionality, 'tech_type'=>$tc_type, 'goto_back'=>$goto_back]);
            }
            return response()->json(['status'=>'200','step'=>$request->left_step+1]);
        }
        catch(\Exception $e)
        {
            return response()->json(['status'=>'422','message'=>$e->getMessage().' '.$e->getFile().' '.$e->getLine()]);
        }
    }

    public function saveStepData($request, $project=null){
        $formData = $request->session()->get('formdata');
        if(empty($formData['type'])) $formData['type'] = 'mobile';

        $formLoopData = $request->session()->get('formloopdata');

        if(!Auth::check()) {
            $project_save = AnonymousClientProject::create($formData);
        }
        else {

            $user = Auth::user();
            $us_uuid = $user->uuid();
            //print_r($us_uuid); exit;
            $formData['user_id'] =  $user->id;
            $formData['user_uuid'] =  $us_uuid;

            $formData['left_step'] =  $formData['left_step']+1;
            if($project){
                $project_save = $project->fill($formData)->save();
            }else{
                $project_save = ClientProject::create($formData);
            }
        }

        if($project && !empty($project->id)) $project_id = $project->id;
        else $project_id = $project_save->id;

        if(!Auth::check()) {

            $client_project_details_tbl = 'anonymous_client_project_details';
            $client_projects_tbl = 'anonymous_client_projects';
        }
        else {

            $client_project_details_tbl = 'client_project_details';
            $client_projects_tbl = 'client_projects';
        }

        //print_r($formLoopData); exit;

        if(!empty($formLoopData)){
            foreach($formLoopData as $flkey=>$fldata){
                foreach($fldata as $flkey2=>$fldata2){
                    $project_id = trim($project_id);
                    $flkey = trim($flkey);
                    $flkey2 = trim($flkey2);
                    $fldata2 = trim($fldata2);

                    #if(!in_array($flkey2, array('left_step', 'is_draft', 'project_number'))){
                        if(DB::table($client_project_details_tbl)->where([ 'project_id' => $project_id, 'type' => $flkey, 'identifier' => $flkey2 ])->exists()){
                            DB::table($client_project_details_tbl)->where([ 'project_id' => $project_id, 'type' => $flkey, 'identifier' => $flkey2 ])->update([ 'project_id' => $project_id, 'type' => $flkey, 'identifier' => $flkey2, 'data' => $fldata2]);
                        } else{
                            DB::table($client_project_details_tbl)->insert([ 'project_id' => $project_id, 'type' => $flkey, 'identifier' => $flkey2, 'data' => $fldata2]);
                        }

                        if($flkey2=='left_step'){
                            DB::table($client_projects_tbl)->where([ 'id' => $project_id ])->update([ 'left_step' => $fldata2 ]);
                        }

                        if($flkey2=='is_draft'){
                            DB::table($client_projects_tbl)->where([ 'id' => $project_id ])->update([ 'is_draft' => $fldata2 ]);
                        }

                        if($flkey2=='project_number'){
                            DB::table($client_projects_tbl)->where([ 'id' => $project_id ])->update([ 'project_number' => $fldata2 ]);
                        }

                        if($flkey2=='tech_type'){
                            DB::table($client_projects_tbl)->where([ 'id' => $project_id ])->update([ 'tech_type' => $fldata2 ]);
                        }
                    }
                    /*DB::table('client_project_details')->upsert(
                        [
                            [
                                'project_id' => $project_id,
                                'type' => $flkey,
                                'identifier' => $flkey2,
                                'data' => $fldata2
                            ]
                        ],
                        [ 'project_id', 'type', 'identifier' ],
                        ['data']
                    );*/
                #}
            }
        }

        if(!empty($formData['left_step']) && in_array($formData['left_step'], array('7'))){
            //echo "hello"; exit;
            $dt_ls = DB::table($client_project_details_tbl)->where([ 'project_id' => $project_id, 'identifier' => 'left_step' ])->orderBy('id', 'desc')->first();
            DB::table($client_project_details_tbl)->where([ 'id' => $dt_ls->id ])->update([ 'data' => $formData['left_step']]);

            $idraft = $formData['is_draft'];
            $dt_ls2 = DB::table($client_project_details_tbl)->where([ 'project_id' => $project_id, 'identifier' => 'is_draft' ])->orderBy('id', 'desc')->first();
            DB::table($client_project_details_tbl)->where(['id' => $dt_ls2->id])->update(['data' => $idraft]);

            $iproject_number = $formData['project_number'];
            $dt_ls3 = DB::table($client_project_details_tbl)->where([ 'project_id' => $project_id, 'identifier' => 'project_number' ])->orderBy('id', 'desc')->first();
            DB::table($client_project_details_tbl)->where(['id' => $dt_ls3->id])->update(['data' => $iproject_number]);

            DB::table($client_projects_tbl)->where(['id' => $project_id])->update(['left_step' => $formData['left_step'], 'is_draft' => $idraft, 'project_number' => $iproject_number]);
        }

        if($project){
            return $project->uuid;
        }else{

            return $project_save->uuid;
        }
    }

    public function saveData($request){
        $formdata = $request->session()->get('formdata');
        $params = [
            'name'                      =>   $formdata['name'],
            'description'               =>   $formdata['description'],
            'example_projects'          =>   serialize($formdata['example_projects']),
            'example_projects_count'    =>   count($formdata['example_projects']),
            'user_id'                   =>   Auth::id(),
            'mockup_url'                =>   $formdata['mockup_url'],
            'type'                      =>   $formdata['type'],
            'user_types'                =>   serialize($formdata['user_types']),
            'number_of_user_types'      =>   $formdata['number_of_user_types'],
            'pages'                     =>   serialize($formdata['pages']),
            'number_of_pages'           =>   $formdata['number_of_pages'],
            'is_draft'                  =>   $request['is_draft'],
            'left_step'                 =>   $request['left_step']
        ];
        if(!empty($formdata['mockup_file'])){
            $params['mockup'] = $formdata['mockup_file'];
        }
        if(!empty($formdata['pages_permission'])){
            $params['pages_permission'] = $formdata['pages_permission'];
        }
        if(!empty($formdata['web_frameworks'])){
            $params['web_frameworks'] = serialize($formdata['web_frameworks']);
        }
        if(!empty($formdata['mobile_frameworks'])){
           $params['mobile_frameworks'] = serialize($formdata['mobile_frameworks']);
        }
        if(!empty($formdata['pages_information'])){
           $params['pages_information'] = $formdata['pages_information'];
        }
        ClientProject::create($params);
    }

    public function getText($textsArray){
        if(!count($textsArray)){
            return ['text'=>''];
        }
        $textLins = [];
        foreach ($textsArray as $text) {
            $textLins[]=['text'=>$text];
        }
        return $textLins;
    }

    public function stepTwo(Request $request){
        return response()->json(['status'=>'200','step'=>3]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientProject  $clientProject
     * @return \Illuminate\Http\Response
     */
    public function generatePdf($project_uuid)
    {
        if(!Auth::check())
        {
            return redirect()->route("client.login.form");
        }
        else
        {
            //$data = DB::select("SELECT * FROM `client_projects` WHERE uuid = UNHEX('" . $uid . "');");
            $data = ClientProject::with('projectQuestions', 'projectType')->whereUuid($project_uuid,'uuid')->first();

            if($data && $data->transaction() && $data->transaction()->status == 2) {
                $project_details = ClientProjectDetail::select('*')->where('project_id', '=', $data->id)->get();
                // return view('client.projects.generate-pdf', compact('data', 'project_details'));
                $pdf = PDF::loadView('client.projects.generate-pdf', compact('data', 'project_details'));
                return $pdf->stream('requirements.pdf');
            }
            return redirect()->route('client.projects');
        }
    }

    public function generatePublicPdf($project_uuid)
    {
        if(!Auth::check())
        {
            return redirect()->route("client.login.form");
        }
        else
        {
            $user = auth()->user();
            $data = DB::select("SELECT * FROM `client_projects` WHERE uuid = UNHEX('" . $project_uuid . "');");
            $data = ClientProject::with('projectQuestions', 'projectType')->find($data[0]->id);
            $has_persmission = PdfPermission::where(['project_id' => $data->id, 'user_id' => $user->id])->first();
            if($data && $data->is_shared && $has_persmission && $data->transaction() && $data->transaction()->status == 2) {
                $project_details = ClientProjectDetail::select('*')->where('project_id', '=', $data->id)->get();
                # return view('client.projects.generate-pdf', compact('data'));
                $pdf = PDF::loadView('client.projects.generate-pdf', compact('data', 'project_details'));
                return $pdf->stream('requirements.pdf');
            }
            return redirect()->route('client.projects');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientProject  $clientProject
     * @return \Illuminate\Http\Response
     */
    public function generatePdf_existing($project_uuid)
    {
        $project = ClientProject::whereUuid($project_uuid,'uuid')->first();
        $data = $project;
        $project_details = $project->getDetails($project->id);
        #return view('client.projects.generate-pdf', compact('data'));
        $pdf = PDF::loadView('client.projects_existing.generate-pdf', compact('data', 'project_details'));

        return $pdf->stream('requirements.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClientProject  $clientProject
     * @return \Illuminate\Http\Response
     */
    public function show(ClientProject $project)
    {
        $data = $project;
        return view('client.projects.generate-pdf', compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientProject  $clientProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $project = ClientProject::find($request->project_id);
        $project->delete();
        return back()->with('success', 'Project Deleted Successfully!');
    }
    public function adduser(Request $request){

        $request->validate([
            'project_id'=>'nullable',
            #'id' => ['required', new CheckUserType()],
            'email' =>'required',
        ]);
        $authUser                   = Auth::user();
        $model                      = new CollaborativeProjects;
        $model->project_id          = $request->project_id;
        $model->assigned_user_email = $request->email;
        $model->user_id             = $authUser->id;
        $model->save();
        $project = ClientProject::find($request->project_id);
        if($request->email){
            $details = [
                'project' => $project->name,
                'user'    => Auth::user()->name,
                'link'    => route('client.projects.edit', [$project->id,'client'])
            ];
           //\Mail::to($request->email)->send(new \App\Mail\ProjectCollaboration($details));
        }
        return response()->json(['status'=>200]);
    }
    public function adduserbyid(Request $request){


        $request->validate([
            'project_id'=>'nullable',
            'developer_id' =>'required',
        ]);
        $developer = User::where('id',$request->developer_id)->first();
        $authUser                   = Auth::user();
        $model                      = new CollaborativeProjects;
        $model->project_id          = $request->project_id;
        $model->assigned_user_email = $developer->email;
        $model->user_id             = $authUser->id;
        $model->save();
        $project = ClientProject::find($request->project_id);
        if($developer->email){
            $details = [
                'project' => $project->name,
                'user'    => Auth::user()->name,
                'link'    => route('client.projects.edit', [$project->id,'client'])
            ];
           //\Mail::to($developer->email)->send(new \App\Mail\ProjectCollaboration($details));
        }
        return response()->json(['status'=>200]);
    }
}