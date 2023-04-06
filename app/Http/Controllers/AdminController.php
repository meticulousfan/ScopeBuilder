<?php

namespace App\Http\Controllers;

use Mail;
use Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Skill;
use App\Models\Skills;
use App\Models\Project;
use App\Models\Question;
use App\Models\Settings;
use App\Models\BbbMeeting;
use Illuminate\Support\Str;
use App\Models\BbbRecording;
use App\Models\ProjectTypes;
use Illuminate\Http\Request;
use App\Models\ClientProject;
use App\Models\PayoutMethods;
use App\Models\PayoutRequest;
use App\Models\Questionnaire;
use App\Models\Questionnaires;
use App\Models\ProjectSettings;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\BbbDefaultJoinSetting;
use App\Models\CollaborativeProjects;
use App\Models\BbbDefaultCreateSetting;
use App\Models\ParentProjectType;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // Show Login Form
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.clients');
            } elseif ($user->hasRole('client')) {
                return redirect()->route('client.projects');
            } elseif ($user->hasRole('freelancer')) {
                return redirect()->route('developer.projects');
            }
        }
        return view('auth.admin.login');
    }

    // Show Forgot Password Form
    public function showForgot()
    {
        return view('auth.admin.forgot');
    }

    // Show Reset Password Form
    public function showReset($token)
    {
        return view('auth.admin.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')->where(['email' => $request->email, 'token' => $request->token])->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect()->route('admin.login.form')->with('success', 'Your password has been changed!');
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $get_email = $request->post('email');
        $user_data = User::where('email', $get_email)->first();
        $uid = $user_data->id;
        $current_role = '1';

        $mhr = DB::table('model_has_roles')->where('model_id', $uid)->get();
        $has_roles = array();
        foreach ($mhr as $role) $has_roles[] = $role->role_id;

        if (in_array($current_role, $has_roles)) {
            $token = Str::random(64);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('emails.forgotPasswordAdmin', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return back()->with('success', 'We have e-mailed your password reset link!');
        } else {
            return back()->with('error', 'Email Not Found!');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Check if user is admin
        $user = User::where('email', $request->email)->first();
        $credentials = $request->only('email', 'password');
        if ($user) {
            if ($user->hasRole('admin')) {
                if (Auth::attempt($credentials)) {
                    return redirect()->route('admin.clients');
                } else {
                    return back()->with('error', 'Credentials do not match!');
                }
            }
            if ($user->hasRole('freelancer')) {
                if (Auth::attempt($credentials)) {
                    return redirect()->route('developer.projects');
                } else {
                    return back()->with('error', 'Credentials do not match!');
                }
            }

            if ($user->hasRole('client')) {
                if (Auth::attempt($credentials)) {
                    // return redirect()->route('.users');
                    // return 'client';
                    return redirect()->route('client.projects');
                } else {
                    return back()->with('error', 'Credentials do not match!');
                }
            }
        }

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Credentials do not match!');
        }
        return redirect()->route('admin.login.form');
    }


    public function clients(Request $request)
    {
        $project_settings = new ProjectSettings();
        $val = $project_settings->get_setting_by_group_key('main', 'pricePerCallMinute');
        $perpage = 10;
        $clients = User::select(
            'users.*',
            DB::raw('COALESCE((SELECT count(client_projects.id) FROM client_projects WHERE client_projects.user_id = users.id),0) as projectNum'),
            DB::raw('sum(IF(bbb_meetings.pending = 0,bbb_meetings.duration,0))*' . $val . ' as totalAmount'),
            DB::raw('sum(IF(bbb_meetings.pending = 0,1,0)) as meetingNum'),
            DB::raw('sum(IF(bbb_meetings.pending = 0,bbb_meetings.duration,0)) as totalDuration'),
        )
            ->join('client_projects', 'users.id', '=', 'client_projects.user_id', 'left')
            ->join('bbb_meetings', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
            ->role('client')
            ->where('users.is_deleted', '!=', '1')
            ->groupBy('users.id')
            ->orderBy('id', 'ASC')->paginate($perpage)->onEachSide(2);

        $clientNum = User::role('client')->count();

        $menu = 'clients';
        return view('admin.clients', compact('clients', 'menu', 'clientNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }
    public function deleteUser(Request $request)
    {
        if ($request->user_type == 1)
            $msg = 'Developer Deleted Successfully!';
        else $msg = 'Client Deleted Successfully!';
        $user = User::find($request->user_id);
        $user->is_deleted = 1;
        $user->deleted_at = date("Y-m-d H:i:s");
        $user->save();
        return back()->with('success', $msg);
    }
    public function changeStatus(Request $request)
    {
        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return back()->with('success', 'Status change Successfully!');
    }
    public function changeCallStatus(Request $request)
    {
        $user = User::find($request->user_id);
        if ($request->type == 0)
            $user->can_take_calls = $request->status;
        else if ($request->type == 1)
            $user->can_start_calls = $request->status;
        $user->save();
        return back()->with('success', 'Call Status change Successfully!');
    }

    public function client_view(Request $request, $uuid)
    {
        $perpage = 5;
        $project_settings = new ProjectSettings();
        $pricePerCallMinute = $project_settings->get_setting_by_group_key('main', 'pricePerCallMinute');
        $client = User::whereUuid($uuid, 'uuid')
            ->select(
                'users.*',
                'countries.country_name',
                DB::raw('COALESCE((SELECT count(client_projects.id) FROM client_projects WHERE client_projects.user_id = users.id),0) as projectNum'),
                DB::raw('sum(IF(bbb_meetings.pending = 0,bbb_meetings.duration,0))*' . $pricePerCallMinute . ' as totalAmount'),
                DB::raw('sum(IF(bbb_meetings.pending = 0,1,0)) as meetingNum'),
                DB::raw('sum(IF(bbb_meetings.pending = 0,bbb_meetings.duration,0)) as totalDuration'),
            )
            ->join('client_projects', 'users.id', '=', 'client_projects.user_id', 'left')
            ->join('bbb_meetings', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
            ->join('countries', 'users.country_id', '=', 'countries.country_id', 'left')
            //->select('users.*', 'countries.country_name')
            ->first();


        $projects = ClientProject::where('client_projects.user_id',  $client->id)->orderBy('id', 'DESC')->paginate($perpage, ['*'], 'projectPagination');

        $meetings = BbbMeeting::select(
            'bbb_meetings.*',
            'transactions.stripe_id',
            'transactions.amount',
            'client_projects.name as project_name'
        )
            ->join('client_projects', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
            ->join('transactions', 'transactions.bbb_meeting_id', '=', 'bbb_meetings.bbb_meeting_id', 'left')
            ->where('client_projects.user_id',  $client->id)
            ->where('bbb_meetings.pending', '=', '0')
            ->orderBy('id', 'DESC')->paginate($perpage, ['*'], 'callsPagination');
        $menu = 'clients';
        return view('admin.profile.client', compact('client', 'menu', 'projects', 'meetings', 'pricePerCallMinute', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }


    public function developers(Request $request)
    {
        $project_settings = new ProjectSettings();
        $val = $project_settings->get_setting_by_group_key('main', 'pricePerCallMinute');
        $perpage = 10;
        $developers = User::select(
            'users.*',
            DB::raw('COALESCE((SELECT count(client_projects.id) FROM client_projects WHERE client_projects.assigned_to_user_id = users.id),0) as projectNum'),
            DB::raw('COALESCE((SELECT SUM(payout_requests.amount) FROM payout_requests WHERE payout_requests.user_id = users.id AND payout_requests.is_paid = 1),0) as totalPaidAmount'),
            DB::raw('COALESCE((SELECT COUNT(projects.id) FROM projects WHERE projects.user_id = users.id),0) as referralNum'),
            DB::raw('COALESCE((SELECT ROUND(SUM(dev_call_ratings.stars)/COUNT(dev_call_ratings.stars),2) FROM dev_call_ratings WHERE dev_call_ratings.dev_id = users.id),0) as rating')
        )
            ->join('client_projects', 'users.id', '=', 'client_projects.user_id', 'left')
            ->role('freelancer')
            ->where('users.is_deleted', '!=', '1')
            ->groupBy('users.id')
            ->orderBy('id', 'ASC')->paginate($perpage)->onEachSide(2);

        $developerNum = User::role('freelancer')->count();

        $menu = 'freelancers';
        return view('admin.developers', compact('developers', 'menu', 'developerNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }

    public function developer_view(Request $request, $uuid)
    {
        $perpage = 5;
        $project_settings = new ProjectSettings();
        $pricePerCallMinute = $project_settings->get_setting_by_group_key('main', 'pricePerCallMinute');
        $developer = User::whereUuid($uuid, 'uuid')
            ->select(
                'users.*',
                DB::raw('COALESCE((SELECT SUM(payout_requests.amount) FROM payout_requests WHERE payout_requests.user_id = users.id AND payout_requests.is_paid = 1),0) as totalPaidAmount'),
                DB::raw('COALESCE((SELECT COUNT(payout_requests.amount) FROM payout_requests WHERE payout_requests.user_id = users.id AND payout_requests.is_paid = 1),0) as payoutNum'),
                DB::raw('COALESCE((SELECT SUM(dev_call_ratings.stars)/COUNT(dev_call_ratings.stars) FROM dev_call_ratings WHERE dev_call_ratings.dev_id = users.id),0) as rating'),
                DB::raw('COALESCE((SELECT count(client_projects.id) FROM client_projects WHERE client_projects.assigned_to_user_id = users.id),0) as projectNum'),
                DB::raw('COALESCE((SELECT COUNT(projects.id) FROM projects WHERE projects.user_id = users.id),0) as referralNum'),
                DB::raw('COALESCE((SELECT sum(IF(bbb_meetings.pending = 0,1,0)) FROM projects WHERE bbb_meetings.developer_id = users.id),0) as meetingNum')
            )
            ->join('client_projects', 'users.id', '=', 'client_projects.assigned_to_user_id', 'left')
            ->join('bbb_meetings', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
            ->first();


        $projects = ClientProject::select('client_projects.*', 'users.name AS client_name', DB::raw(" LOWER(CONCAT(SUBSTR(HEX(users.uuid), 1, 8), '-',SUBSTR(HEX(users.uuid), 9, 4), '-',SUBSTR(HEX(users.uuid), 13, 4), '-',SUBSTR(HEX(users.uuid), 17, 4), '-',SUBSTR(HEX(users.uuid), 21))) As client_uuid"))
            ->join('users', 'users.id', '=', 'client_projects.user_id', 'left')->where('client_projects.assigned_to_user_id',  $developer->id)->orderBy('id', 'DESC')->paginate($perpage, ['*'], 'projectPagination');

        $referrals = Project::select(
            'projects.*',
            'users.name AS client_name',
            DB::raw(" LOWER(CONCAT(SUBSTR(HEX(users.uuid), 1, 8), '-',SUBSTR(HEX(users.uuid), 9, 4), '-',SUBSTR(HEX(users.uuid), 13, 4), '-',SUBSTR(HEX(users.uuid), 17, 4), '-',SUBSTR(HEX(users.uuid), 21))) As client_uuid"),
            DB::raw('COUNT(projects.id) as projectNum')
        )
            ->join('users', 'users.id', '=', 'projects.client_id', 'left')
            ->where('projects.user_id', $developer->id)
            ->groupBy('projects.client_id')
            ->orderBy('id', 'DESC')->paginate($perpage, ['*'], 'referralsPagination');

        $meetings = BbbMeeting::select(
            'bbb_meetings.*',
            'client_projects.name as project_name',
            'transactions.stripe_id',
            'transactions.amount',
            DB::raw(" LOWER(CONCAT(SUBSTR(HEX(bbb_meetings.uuid), 1, 8), '-',SUBSTR(HEX(bbb_meetings.uuid), 9, 4), '-',SUBSTR(HEX(bbb_meetings.uuid), 13, 4), '-',SUBSTR(HEX(bbb_meetings.uuid), 17, 4), '-',SUBSTR(HEX(bbb_meetings.uuid), 21))) As bbb_meetings_uuid")
        )
            ->join('client_projects', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
            ->join('transactions', 'transactions.bbb_meeting_id', '=', 'bbb_meetings.bbb_meeting_id', 'left')
            ->where('bbb_meetings.developer_id',  $developer->id)
            ->where('bbb_meetings.pending', '=', '0')
            ->orderBy('id', 'DESC')->paginate($perpage, ['*'], 'callsPagination');

        $payouts = PayoutRequest::select('payout_requests.*')
            ->where('payout_requests.user_id',  $developer->id)
            ->orderBy('id', 'DESC')->paginate($perpage, ['*'], 'payoutsPagination');
        $menu = 'freelancers';
        return view('admin.profile.developer', compact('developer', 'menu', 'projects', 'referrals', 'meetings', 'payouts', 'pricePerCallMinute', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }
    public function recording($recording_uuid)
    {
        $record = BbbRecording::whereUuid($recording_uuid, "uuid")->first();
        $url = "";
        if ($record) {
            $url = $record->url;
        }
        $menu = 'recordings';
        return view('admin.projects.record', compact("url", "menu"));
    }
    public function projects(Request $request)
    {
        if (Auth::check()) {
            $projectsNum = ClientProject::count();
            $perpage = 10;
            //
            $projects = ClientProject::withTrashed()->select(
                'client_projects.*',
                DB::raw('COALESCE((SELECT COUNT(collaborative_projects.id) FROM collaborative_projects WHERE collaborative_projects.project_id = client_projects.id),0) as collaborativeNum'),
                DB::raw('sum(IF(bbb_meetings.pending = 0,1,0)) as meetingNum')
            )
                ->join('bbb_meetings', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
                ->join('users', 'users.id', '=', 'client_projects.user_id', 'left')
                ->where('users.is_deleted', '!=', '1')
                ->groupBy('client_projects.id')
                ->orderBy('client_projects.id', 'ASC')
                ->paginate($perpage)->onEachSide(2);
            $menu = 'client_projects';
            return view('admin.projects.client_projects', compact('projects', 'menu', 'projectsNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
        } else {
            return route('admin.login.form');
        }
    }
    public function project_view(Request $request, $uuid)
    {
        $perpage = 5;

        $project = ClientProject::select(
            'client_projects.*',
            'users.name AS client_name',
            DB::raw(" LOWER(CONCAT(SUBSTR(HEX(users.uuid), 1, 8), '-',SUBSTR(HEX(users.uuid), 9, 4), '-',SUBSTR(HEX(users.uuid), 13, 4), '-',SUBSTR(HEX(users.uuid), 17, 4), '-',SUBSTR(HEX(users.uuid), 21))) As client_uuid"),
            DB::raw('sum(IF(bbb_meetings.pending = 0,bbb_meetings.duration,0)) as totalDuration'),
            DB::raw('sum(IF(bbb_meetings.pending = 0,1,0)) as meetingNum'),
            DB::raw('COALESCE((SELECT count(collaborative_projects.id) FROM collaborative_projects WHERE collaborative_projects.project_id = client_projects.id),0) as collaborativeNum')
        )
            ->join('users', 'users.id', '=', 'client_projects.user_id', 'left')
            ->join('bbb_meetings', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
            ->whereUuid($uuid, 'uuid')->first();

        $meetings = BbbMeeting::select(
            'bbb_meetings.*',
            'client_projects.name as project_name',
            'transactions.amount as amount',
            'transactions.stripe_id',
            DB::raw(" LOWER(CONCAT(SUBSTR(HEX(bbb_meetings.uuid), 1, 8), '-',SUBSTR(HEX(bbb_meetings.uuid), 9, 4), '-',SUBSTR(HEX(bbb_meetings.uuid), 13, 4), '-',SUBSTR(HEX(bbb_meetings.uuid), 17, 4), '-',SUBSTR(HEX(bbb_meetings.uuid), 21))) As bbb_meetings_uuid")
        )
            ->join('client_projects', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
            ->join('transactions', 'transactions.bbb_meeting_id', '=', 'bbb_meetings.bbb_meeting_id', 'left')
            ->where('client_projects.id',  $project->id)
            ->where('bbb_meetings.pending', '=', '0')
            ->orderBy('id', 'DESC')->paginate($perpage, ['*'], 'callsPagination');
        $collaborators = CollaborativeProjects::select('collaborative_projects.*', DB::raw("IF(users.name IS NULL ,collaborative_projects.assigned_user_email,users.name) AS collaborator_name"))
            ->join('users', 'users.email', '=', 'collaborative_projects.assigned_user_email', 'left')
            ->where('collaborative_projects.project_id',  $project->id)
            ->orderBy('collaborative_projects.id', 'DESC')->paginate($perpage, ['*'], 'collaboratorsPagination');

        $menu = 'freelancers';
        return view('admin.projects.view', compact('project', 'menu', 'meetings', 'collaborators',  'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }
    public function deleteProject(Request $request)
    {
        $user = ClientProject::withTrashed()->where('id', $request->project_id)->first();
        $user->forceDelete();
        return back()->with('success', 'Project Deleted Successfully!');
    }

    public function projects_assign($project_uuid)
    {
        if (Auth::check()) {
            $project = ClientProject::whereUuid($project_uuid, 'uuid')->first();
            #echo "<pre>"; print_r($project->assigned_to_user_id); exit;
            $developers = User::role('freelancer')->orderBy('id', 'DESC')->get();
            return view('admin.project_assign', compact('project', 'developers'));
        } else {
            return route('admin.login.form');
        }
    }

    public function projects_assign_submit(Request $request, $project_uuid)
    {
        $request->validate([
            'assigned_to_user_id' => 'required',
        ]);

        $assign_id = $request->post('assigned_to_user_id');

        $project = ClientProject::whereUuid($project_uuid, 'uuid')->first();
        $project->assigned_to_user_id = $assign_id;
        $project->save();
        return redirect()->route('admin.projects')->with('success', 'Developer is assigned successfully!');
    }

    public function venttedprojects(Request $request)
    {
        $perpage = 10;
        $projects = ClientProject::select('client_projects.*', DB::raw(" LOWER(CONCAT(SUBSTR(HEX(users.uuid), 1, 8), '-',SUBSTR(HEX(users.uuid), 9, 4), '-',SUBSTR(HEX(users.uuid), 13, 4), '-',SUBSTR(HEX(users.uuid), 17, 4), '-',SUBSTR(HEX(users.uuid), 21))) As client_uuid"))
            ->join('users', 'users.id', '=', 'client_projects.user_id')
            ->orderBy('users.id', 'DESC')->where('users.name', 'LIKE', '%' . $request->input('search') . '%')->where('contact_developer', '1')->paginate($perpage)->onEachSide(2);
        $developers = User::role('freelancer')->orderBy('id', 'DESC')->get();
        $menu = 'venttedprojects';
        return view('admin.venttedprojects', compact('projects', 'menu',  'developers', 'perpage'))->with('search', $request->input('search'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }
    public function recordings(Request $request)
    {
        $perpage = 10;
        $recordings = BbbRecording::select(
            'bbb_recordings.*',
            'client.name as client_name',
            'developer.name as developer_name',
            'dev_call_ratings.stars as rating',
            'dev_call_ratings.message as review',
            DB::raw(" LOWER(CONCAT(SUBSTR(HEX(bbb_recordings.uuid), 1, 8), '-',SUBSTR(HEX(bbb_recordings.uuid), 9, 4), '-',SUBSTR(HEX(bbb_recordings.uuid), 13, 4), '-',SUBSTR(HEX(bbb_recordings.uuid), 17, 4), '-',SUBSTR(HEX(bbb_recordings.uuid), 21))) As bbb_recordings_uuid"),
            'client_projects.name As project_name',
            DB::raw(" LOWER(CONCAT(SUBSTR(HEX(client_projects.uuid), 1, 8), '-',SUBSTR(HEX(client_projects.uuid), 9, 4), '-',SUBSTR(HEX(client_projects.uuid), 13, 4), '-',SUBSTR(HEX(client_projects.uuid), 17, 4), '-',SUBSTR(HEX(client_projects.uuid), 21))) As project_uuid")
        )
            ->join('bbb_meetings', 'bbb_recordings.bbb_meeting_id', '=', 'bbb_meetings.bbb_meeting_id', 'left')
            ->join('bbb_meeting_participants', 'bbb_meeting_participants.meeting_id', '=', 'bbb_meetings.id')
            ->join('client_projects', 'client_projects.id', '=', 'bbb_meetings.project_id', 'left')
            ->join('users as client', 'client_projects.user_id', '=', 'client.id', 'left')
            ->join('users as developer', 'bbb_meeting_participants.user_id', '=', 'developer.id', 'left')
            ->join('dev_call_ratings', 'bbb_recordings.id', '=', 'dev_call_ratings.bbbRecordid', 'left')
            ->where('client_projects.name', 'LIKE', '%' . $request->input('search') . '%')
            ->paginate($perpage)->onEachSide(2);
        $menu = 'recordings';
        return view('admin.recordings', compact('recordings', 'menu', 'perpage'))->with('search', $request->input('search'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }

    public function edit_existing(ClientProject $project, Request $request)
    {
        if ($project->id != $request->session()->get('current_project_id')) {
            $request->session()->forget('formdata');
            $request->session()->forget('formloopdata');
        }
        $request->session()->put('current_project_id', $project->id);

        if (!empty($project->editing_user_id) && $project->editing_user_id != Auth::id()) {
            $user = User::where('id', $project->editing_user_id)->first();
            $request->session()->flash('error', $user->name . ' is currently editing the form, any changes saved would cause their work to be lost');
        } else {
            $project->editing_user_id = Auth::id();
            $project->save();
        }

        $project_details = $project->getDetails($project->id);

        $mode_type = 'edit';
        return view('client.projects_existing.create', compact('project', 'project_details', 'mode_type'));
    }
    // Destroy User
    public function destroyUser(Request $request)
    {
        $user = User::find($request->user_id);
        $user->is_deleted = 1;
        $user->deleted_at = date("Y-m-d H:i:s");
        $user->save();
        return back()->with('success', 'User Deleted Successfully!');
    }

    public function myProfile()
    {
        if (Auth::check()) {
            $menu = 'profile';
            return view('admin.profile.index', compact('menu'));
        } else {
            return route('admin.login.form');
        }
    }

    public function showSecuritySettings()
    {
        if (Auth::check()) {
            $menu = 'profile';
            return view('admin.profile.security-settings', compact('menu'));
        } else {
            return route('admin.login.form');
        }
    }

    public function showPaymentSettings()
    {
        if (Auth::check()) {
            return view('admin.profile.payment-settings');
        } else {
            return route('admin.login.form');
        }
    }

    public function settings_index()
    {
        if (Auth::check()) {
            $settings = Settings::first();

            $menu = 'settings';
            return view('admin.settings.index', compact('settings', 'menu'));
        } else {
            return route('admin.login.form');
        }
    }

    public function showCallsSettings(ProjectSettings $project_settings)
    {
        if (Auth::check()) {
            $settings = Settings::first();
            $bbb_default_create_settings = BbbDefaultCreateSetting::first();
            $bbb_default_join_settings = BbbDefaultJoinSetting::first();
            $menu = 'settings';
            return view('admin.settings.calls-settings', compact('settings', 'menu', 'bbb_default_create_settings', 'bbb_default_join_settings'));
        } else {
            return route('admin.login.form');
        }
    }
    public function seoSettings(ProjectSettings $project_settings)
    {
        if (Auth::check()) {
            $settings = Settings::first();
            $menu = 'settings';
            return view('admin.settings.seo', compact('settings', 'menu'));
        } else {
            return route('admin.login.form');
        }
    }


    // Update Profile
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        // Get current user
        $user = User::findOrFail(auth()->user()->id);
        $user->name = $request->name;
        // Persist user record to database
        $user->update();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('image')->toMediaCollection('avatar');
        }

        // Return user back and show a flash message
        return redirect()->back()->with(['success' => 'Profile updated successfully.']);
    }

    // Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required'],
            'new_password' => ['required'],
            'confirm_new_password' => ['same:new_password'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Current password does not match!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password successfully changed!');
    }

    // Update Payment Methods
    public function updatePaymentMethods(Request $request)
    {
        $request->validate([
            'paypal_email' => 'required|email',
            'payoneer_email' => 'nullable',
        ]);

        $user = Auth::user();

        $user->paypal_email = $request->paypal_email;
        $user->payoneer_email = !empty($request->payoneer_email) ? $request->payoneer_email : '';
        $user->save();

        return back()->with('success', 'Payment Information successfully changed!');
    }

    //update settings
    public function settings_index_submit(Request $request, ProjectSettings $project_settings)
    {
        $request_data = $request->post('data');

        if (isset($request_data['settings']['numberOfDevsAvailable'])) {
            $file = $request->file("upload_file");
            if ($file) {
                $validatedData = $request->validate([
                    'file' => 'upload_file|mp3,ogg,wav|max:2048',
                ]);
                $filename = $file->getClientOriginalName();
                $filename = pathinfo($filename, PATHINFO_FILENAME);
                if (strlen($filename) > 8) $filename = substr($filename, 0, 8);
                $filename = $filename . '_' . time() . '.' . $file->extension();

                $file->move(public_path('upload'), $filename);
                $project_settings->setSetting('settings', 'alertSound', $filename);
            }
        }
        $file = $request->file("metaImage");
        if ($file) {
            $validatedData = $request->validate([
                'file' => 'upload_file|mp3,ogg,wav|max:2048',
            ]);
            $filename = $file->getClientOriginalName();
            $filename = pathinfo($filename, PATHINFO_FILENAME);
            if (strlen($filename) > 8) $filename = substr($filename, 0, 8);
            $filename = $filename . '_' . time() . '.' . $file->extension();

            $file->move(public_path('upload'), $filename);
            $project_settings->setSetting('settings', 'metaImage', $filename);
        }

        #var_dump($request_data);exit;
        foreach ($request_data as $itr1k => $itr1v) {
            $crgroup = $itr1k;
            foreach ($itr1v as $itr2k => $itr2v) {
                $crkey = $itr2k;
                $crval = $itr2v ?? '';
                $project_settings->setSetting($crgroup, $crkey, $crval);
            }
        }
        return back()->with('success', 'Settings successfully changed!');
    }

    // Payouts
    public function payouts()
    {
        if (Auth::check()) {
            $perpage = 10;
            $payouts = PayoutRequest::select(
                'payout_requests.*',
                'users.name AS user_name',
                'users.email AS user_email',
                'users.paypal_email AS  email',
                DB::raw(" LOWER(CONCAT(SUBSTR(HEX(users.uuid), 1, 8), '-',SUBSTR(HEX(users.uuid), 9, 4), '-',SUBSTR(HEX(users.uuid), 13, 4), '-',SUBSTR(HEX(users.uuid), 17, 4), '-',SUBSTR(HEX(users.uuid), 21))) As user_uuid"),
                DB::raw("payout_methods.name  AS method_name")
            )
                ->join('users', 'users.id', '=', 'payout_requests.user_id', 'left')
                ->join('payout_methods', 'payout_methods.id', '=', 'payout_requests.payment_method')
                ->orderBy('payout_requests.created_at', 'DESC')
                ->where('is_paid', '0')->paginate($perpage)->onEachSide(2);
            $menu = 'payouts';
            return view('admin.payouts.pending', compact('payouts', 'menu', 'perpage'));
        } else {
            return route('admin.login.form');
        }
    }
    public function acceptedPayouts()
    {
        if (Auth::check()) {
            $perpage = 10;
            $payouts = PayoutRequest::select(
                'payout_requests.*',
                'users.name AS user_name',
                'users.paypal_email AS  email',
                DB::raw(" LOWER(CONCAT(SUBSTR(HEX(users.uuid), 1, 8), '-',SUBSTR(HEX(users.uuid), 9, 4), '-',SUBSTR(HEX(users.uuid), 13, 4), '-',SUBSTR(HEX(users.uuid), 17, 4), '-',SUBSTR(HEX(users.uuid), 21))) As user_uuid"),
                DB::raw("payout_methods.name  AS method_name")
            )
                ->join('users', 'users.id', '=', 'payout_requests.user_id', 'left')
                ->join('payout_methods', 'payout_methods.id', '=', 'payout_requests.payment_method')
                ->orderBy('payout_requests.created_at', 'DESC')
                ->where('is_paid', '1')->paginate($perpage)->onEachSide(2);
            $menu = 'payouts';
            return view('admin.payouts.accepted_payouts', compact('payouts', 'menu', 'perpage'));
        } else {
            return route('admin.login.form');
        }
    }
    public function rejectedPayouts()
    {
        if (Auth::check()) {
            $perpage = 10;
            $payouts = PayoutRequest::select(
                'payout_requests.*',
                'users.name AS user_name',
                'users.paypal_email AS  email',
                DB::raw(" LOWER(CONCAT(SUBSTR(HEX(users.uuid), 1, 8), '-',SUBSTR(HEX(users.uuid), 9, 4), '-',SUBSTR(HEX(users.uuid), 13, 4), '-',SUBSTR(HEX(users.uuid), 17, 4), '-',SUBSTR(HEX(users.uuid), 21))) As user_uuid"),
                DB::raw("payout_methods.name  AS method_name")
            )
                ->join('users', 'users.id', '=', 'payout_requests.user_id', 'left')
                ->join('payout_methods', 'payout_methods.id', '=', 'payout_requests.payment_method')
                ->orderBy('payout_requests.created_at', 'DESC')
                ->where('is_paid', '2')->paginate($perpage)->onEachSide(2);
            $menu = 'payouts';
            return view('admin.payouts.rejected_payouts', compact('payouts', 'menu', 'perpage'));
        } else {
            return route('admin.login.form');
        }
    }
    public function payoutAction(Request $request)
    {
        $payoutRequest = PayoutRequest::find($request->request_id);
        $payoutRequest->is_paid = $request->is_paid;
        $payoutRequest->note = $request->note;
        $payoutRequest->save();
        if ($request->is_paid == 1)
            $details = [
                'is_paid' => '1',
                'url' => route('admin.payouts'),
                'text' => 'Good news! Your Payout request of $' . number_format(round(floatval($payoutRequest->amount), 2), 2) . ' was accepted.',
                'notes' => $request->note
            ];
        else
            $details = [
                'is_paid' => '2',
                'url' => route('developer.payouts'),
                'text' => 'Your Payout request of $' . number_format(round(floatval($payoutRequest->amount), 2), 2) . ' was rejected.',
                'notes' => $request->note
            ];

        \Mail::to($request->request_email)->send(new \App\Mail\PayoutEmail($details));

        return back()->with('success', 'Request changed Successfully!');
    }
    // payout_methods
    public function payout_methods()
    {
        if (Auth::check()) {
            $methods = PayoutMethods::all();
            $menu = 'payout_methods';
            return view('admin.settings.payout_methods', compact('methods', 'menu'));
        } else {
            return route('admin.login.form');
        }
    }
    public function payout_methodsChangeStatus(Request $request)
    {
        $payoutMethods = PayoutMethods::find($request->method_id);
        $payoutMethods->status = $request->status;
        $payoutMethods->save();
        return back()->with('success', 'Status changed Successfully!');
    }



    // Logout
    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('admin.login.form');
    }
    public function projectTypes(Request $request)
    {
        $perpage = 10;
        $projectTypes = ParentProjectType::with('projectTypes')->paginate($perpage)->onEachSide(2);
        $count = ParentProjectType::with('projectTypes')->paginate($perpage)->onEachSide(2)->count();
        $skills = Skill::all();
        $projectTypesNum = ParentProjectType::count();
        $menu = 'types';
        return view('admin.projects.types', compact('projectTypes', 'menu', 'projectTypesNum', 'perpage', 'skills', 'count'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }
    public function deleteProjectType(Request $request)
    {
        $projectType = ProjectTypes::find($request->type_id);
        foreach (Questionnaire::where('type_id', $projectType->id)->get() as $questionnaire) {
            Question::where('questionnaire_id', $questionnaire->id)->delete();
            $questionnaire->delete();
        }
        $projectType->delete();
        return back()->with('success', 'Sub project Type Deleted Successfully!');
    }
    public function deleteParentProjectType(Request $request)
    {
        $projectType = ParentProjectType::find($request->type_id);
        foreach (ProjectTypes::where('parent_id', $projectType->id)->get() as $subProjectType) {
            foreach (Questionnaire::where('type_id', $subProjectType->id)->get() as $questionnaire) {
                Question::where('questionnaire_id', $questionnaire->id)->delete();
                $questionnaire->delete();
            }
        }
        ProjectTypes::where('parent_id', $projectType->id)->delete();
        $projectType->delete();
        return back()->with('success', 'Parent project type Deleted Successfully!');
    }
    public function changeStatusProjectType(Request $request)
    {
        $projectType = ProjectTypes::find($request->type_id);
        $projectType->status = $request->status;
        $projectType->save();
        return back()->with('success', 'Status changed Successfully!');
    }
    public function changeParentStatusProjectType(Request $request)
    {
        $projectType = ParentProjectType::find($request->type_id);
        $projectType->status = $request->status;
        $projectType->save();
        return back()->with('success', 'Parent project status changed Successfully!');
    }
    // public function storeProjectType(Request $request)
    // {
    //     if ($request->sub_type_id != 0) {
    //         $projectType = ProjectTypes::find($request->sub_type_id);
    //         $projectType->name = $request->sub_type_name;
    //         $projectType->status = 1;
    //         $projectType->save();
    //         return back()->with('success', 'Sub Project Type Added Successfully!');
    //     } else {
    //         $projectType = new ProjectTypes();
    //         $projectType->name = $request->sub_type_name;
    //         $projectType->parent_id = $request->parent_type_id;
    //         $projectType->status = 1;
    //         $projectType->save();
    //         return back()->with('success', 'Sub Project Type changed Successfully!');
    //     }
    // }

    public function storeProjectType(Request $request)
    {
        $types = (array) $request->all();

        if ($request->project_type_id) {
            $parentProjectTypes = ParentProjectType::find($request->project_type_id);
            $parentProjectTypes->name = $request->p_type_name;
            $parentProjectTypes->status = 1;
            $parentProjectTypes->save();
        } else {
            $parentProjectTypes = new ParentProjectType();
            $parentProjectTypes->name = $request->p_type_name;
            $parentProjectTypes->status = 1;
            $parentProjectTypes->save();
        }

        ProjectTypes::where('parent_id', $request->project_type_id)->delete();
        if (array_key_exists('type', $types) && count($types['type'])) {
            foreach ($types['type'] as $type) {
                $projectType = new ProjectTypes();
                $projectType->name = $type['s_type_name'];
                $projectType->parent_id = $parentProjectTypes->id;
                $projectType->status = 1;
                $projectType->skills = array_key_exists('skills', $type) ? json_encode((implode(',', $type['skills']))) : null;
                $projectType->save();
            }
        }

        return back()->with('success', 'Updated Project Type Successfully!');
    }
    public function storeSkills(Request $request)
    {
        $projectId = $request->sub_type_id;
        $skillIds = implode(",", (array) $request->skills);
        $projectType = ProjectTypes::find($projectId);
        if ($projectType) {
            $projectType->skills = json_encode($skillIds);
            $projectType->save();
            return back()->with('success', 'Assigned Skills Successfully!');
        }
    }

    public function storeParentProjectType(Request $request)
    {
        if ($request->type_id != 0) {
            $projectType = ParentProjectType::find($request->type_id);
            $projectType->name = $request->type_name;
            $projectType->status = 1;
            $projectType->save();
            return back()->with('success', 'Parent Project Type changed Successfully!');
        } else {
            $projectType = new ParentProjectType();
            $projectType->name = $request->type_name;
            $projectType->status = 1;
            $projectType->save();
            return back()->with('success', 'Parent project type added Successfully!');
        }
    }

    public function skills(Request $request)
    {
        $perpage = 10;
        $skills = Skills::select('skills.*', 'users.name AS user_name')
            ->join('users', 'skills.suggested_by_id', '=', 'users.id', 'left')
            ->paginate($perpage)->onEachSide(2);
        $skillsNum = Skills::count();
        $menu = 'skills';
        return view('admin.projects.skills', compact('skills', 'menu', 'skillsNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }
    public function deleteSkill(Request $request)
    {
        $skill = Skills::find($request->skill_id);
        $skill->delete();
        return back()->with('success', 'Skill Deleted Successfully!');
    }
    public function changeStatusSkill(Request $request)
    {
        $skill = Skills::find($request->skill_id);
        $skill->status = $request->status;
        $skill->save();
        return back()->with('success', 'Status changed Successfully!');
    }
    public function storeSkill(Request $request)
    {
        $is_exist = Skills::where('id', '!=', $request->skill_id)->where('name', $request->skill_name)->count();
        if ($is_exist > 0) return back()->with('error', 'A skill with the same name exists!');
        if ($request->skill_id != 0) {
            $skill = Skills::find($request->skill_id);
            $skill->name = $request->skill_name;
            $skill->save();
            return back()->with('success', 'Skill changed Successfully!');
        } else {
            $skill = new Skills();
            $skill->name = $request->skill_name;
            $skill->status = 0;
            $skill->save();
            return back()->with('success', 'Skill Added Successfully!');
        }
    }
    public function suggestSkill(Request $request)
    {
        $is_exist = Skills::where('id', '!=', $request->skill_id)->where('name', $request->skill_name)->count();
        if ($is_exist > 0) return back()->with('error', 'A skill with the same name exists!');
        $skill = new Skills();
        $skill->name = $request->skill_name;
        $skill->status = 0;
        $skill->suggested_by_id = auth()->user()->id;
        $skill->suggested_on = date("Y-m-d H:i:s");
        $skill->save();
        return back()->with('success', 'Skill Suggested Successfully!');
    }
    public function questionnaires(Request $request)
    {
        $perpage = 10;
        $questionnaires = Questionnaires::select('questionnaires.*', 'project_types.name AS project_type_name')
            ->join('project_types', 'questionnaires.type_id', '=', 'project_types.id', 'left')
            ->paginate($perpage)->onEachSide(2);
        $questionnairesNum = Questionnaires::count();
        $menu = 'questionnaires';
        return view('admin.projects.questionnaires', compact('questionnaires', 'menu', 'questionnairesNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }
    public function deleteQuestionnaire(Request $request)
    {
        $questionnaire = Questionnaires::find($request->questionnaire_id);
        $questionnaire->delete();
        return back()->with('success', 'Questionnaire Deleted Successfully!');
    }
    public function changeStatusQuestionnaire(Request $request)
    {
        $questionnaire = Questionnaires::find($request->questionnaire_id);
        $questionnaire->status = $request->status;
        $questionnaire->save();
        return back()->with('success', 'Status changed Successfully!');
    }
    public function createQuestion(Request $request)
    {
        $perpage = 10;
        $skills = [];
        $skillsNum = 0;
        $menu = 'questionnaires';
        return view('admin.projects.edite_question', compact('skills', 'menu',  'skillsNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }

    public function skillsList()
    {
        $skills = Skill::get()->toArray();

        return response()->json(["skills" => $skills], 200);
    }

    public function projectTypesList()
    {
        $type_ids = Questionnaire::groupBy('type_id')->pluck('type_id');
        $projectTypes = ProjectTypes::whereNotIn('id', $type_ids)->get()->toArray();

        return response()->json(["project_types" => $projectTypes], 200);
    }

    public function adminProjectTypesList()
    {
        $projectTypes = ParentProjectType::with('projectTypes')->get()->toArray();

        return response()->json(["project_types" => $projectTypes], 200);
    }

    public function listQuestionnaires()
    {
        $questionnaires = Questionnaire::with(['skills', 'project_types'])->get()->toArray();

        return response()->json(["questionnaires" => $questionnaires], 200);
    }

    public function createQuestionnaireForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|numeric',
            'skills_ids' => 'required|array',
            'is_default' => 'required|boolean',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project_type = ProjectTypes::find($request->type_id);
        if (!$project_type) {
            return response()->json(['errors' => __('frontend.project_type_not_found')], 404);
        }

        foreach ($request->skills_ids as $skill) {
            if (!Skill::find($skill)) {
                return response()->json(['errors' => __('frontend.skill_not_found')], 404);
            }
        }

        $is_exist = Questionnaire::where('type_id', $request->type_id)->first();
        if($is_exist) {
            return response()->json(['errors' => __('frontend.questionnaire_not_created_same_type')], 200);
        }

        $questionnaire = new Questionnaire();
        $questionnaire->name = $request->name;
        $questionnaire->type_id = $request->type_id;

        if (!Questionnaire::where('type_id', $request->type_id)->exists()) {
            $questionnaire->is_default = true;
        } else {
            if ($request->is_default == true) {
                Questionnaire::where('type_id', $request->type_id)->update(['is_default' => false]);
            }
            $questionnaire->is_default = $request->is_default;
        }

        $questionnaire->save();

        foreach ($request->skills_ids as $key) {
            if (!$questionnaire->skills->contains($key)) {
                $questionnaire->skills()->save(Skill::find($key));
            }
        }

        return response()->json(["message" => __('frontend.questionnaire_registered_success')], 200);
    }

    public function showQuestionnaireForm(int $id)
    {
        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }

        return response()->json(["questionnaire" => $questionnaire], 200);
    }

    public function editQuestionnaireForm(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|numeric',
            'skills_ids' => 'required|array',
            'is_default' => 'required|boolean',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project_type = ProjectTypes::find($request->type_id);
        if (!$project_type) {
            return response()->json(['errors' => __('frontend.project_type_not_found')], 404);
        }

        foreach ($request->skills_ids as $skill) {
            if (!Skill::find($skill)) {
                return response()->json(['errors' => __('frontend.skill_not_found')], 404);
            }
        }

        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }

        $is_exist = Questionnaire::where('id', '!=', $id)->where('type_id', $request->type_id)->first();
        if($is_exist) {
            return response()->json(['errors' => __('frontend.questionnaire_not_created_same_type')], 200);
        }

        $questionnaire->name = $request->name;
        $questionnaire->type_id = $request->type_id;
        if ($request->is_default == true) {
            Questionnaire::where('type_id', $request->type_id)->update(['is_default' => false]);
        }
        $questionnaire->is_default = $request->is_default;
        $questionnaire->save();
        foreach ($request->skills_ids as $key) {
            if (!$questionnaire->skills->contains($key)) {
                $questionnaire->skills()->save(Skill::find($key));
            }
        }

        return response()->json(["message" => __('frontend.questionnaire_updated_success')], 200);
    }

    public function editQuestionnaireFormStatus(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), ['status' => 'required|boolean']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }
        $questionnaire->status = $request->status;
        $questionnaire->save();

        return response()->json(["message" => __('frontend.questionnaire_updated_success')], 200);
    }

    public function deleteQuestionnaireForm(int $id)
    {
        $questionnaire = Questionnaire::find($id);
        $questionnaire->skills()->detach();
        $questionnaire->questions()->delete();
        $questionnaire->delete();

        return response()->json(["message" => __('frontend.questionnaire_deleted_success')], 200);
    }

    public function addStepToQuestionnaireForm(Request $request, int $id)
    {
        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }
        $questionnaire->step += 1;
        $questionnaire->save();

        return response()->json(["message" => __('frontend.step_added_questionnaire')], 200);
    }

    public function removeStepToQuestionnaireForm(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), ['step_id' => 'required|numeric']);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $questionnaire = Questionnaire::find($id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }

        Question::where(['questionnaire_id' => $questionnaire->id, 'step' => $request->step_id])->delete();
        $questionnaire->step -= 1;
        $questionnaire->save();

        return response()->json(["message" => __('frontend.step_removed_questionnaire')], 200);
    }

    public function questionsQuestionnaireForm(int $questionnaire_id, int $step)
    {
        $questionnaire = Questionnaire::find($questionnaire_id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }
        $questions = Question::where(['questionnaire_id' => $questionnaire->id, 'step' => $step])->orderBy('position', 'ASC')->get()->toArray();

        return response()->json(["questions" => $questions], 200);
    }

    public function createQuestionField(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'questionnaire_id' => 'required|numeric',
            'step' => 'required|numeric',
            'fields.*.' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $questionnaire = Questionnaire::find($request->questionnaire_id);
        if (!$questionnaire) {
            return response()->json(['errors' => __('frontend.questionnaire_not_found')], 404);
        }
        $count = 1;
        $fields = json_decode($request->fields, true);
        foreach ($fields as $field) {
            $question = new Question();
            $question->fields = $field;
            $question->step = $request->step;
            $question->position = $count;
            $question->questionnaire()->associate($questionnaire);
            $question->save();

            $count += 1;
        }

        return response()->json(["message" => __('frontend.question_added_success')], 200);
    }

    public function showQuestionField(int $id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['errors' => __('frontend.question_not_found')], 404);
        }

        return response()->json(["question" => $question], 200);
    }

    public function editQuestionField(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'fields' => 'required|json',
            'position' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $question = Question::find($id);
        if (!$question) {
            return response()->json(['errors' => __('frontend.question_not_found')], 404);
        }
        if ($request->position != NULL) {
            $new_position = $request->position;

            $old_position = $question->position;

            $other_question = Question::where(['questionnaire_id' => $question->questionnaire_id, 'step' => $question->step, 'position' => $new_position])->first();
            $other_question->position = $old_position;
            $other_question->save();

            $question->position = $new_position;
        }
        $question->fields = json_decode($request->fields, true);
        $question->save();

        return response()->json(["message" => __('frontend.question_updated_success')], 200);
    }

    public function deleteQuestionField(int $id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['errors' => __('frontend.question_not_found')], 404);
        }
        $question->delete();

        return response()->json(["message" => __('frontend.question_deleted_success')], 200);
    }

    public function manageQuestions(Request $request, int $id)
    {
        $perpage = 10;
        $skills = [];
        $skillsNum = 0;
        $menu = 'questions-new';
        return view('admin.questionnaire.manage_questions', compact('skills', 'menu',  'skillsNum', 'perpage', 'id'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }

    public function editQuestion(Request $request, int $id)
    {
        $question = Question::find($id);
        if (!$question) {
            return back()->with(['errors' => __('frontend.question_not_found')]);
        }
        $countQuestion = Question::where(['questionnaire_id' => $question->questionnaire_id, 'step' => $question->step])->count();
        $perpage = 10;
        $skills = [];
        $skillsNum = 0;
        $menu = 'question-edit';
        return view('admin.questionnaire.edit_question', compact('skills', 'menu',  'skillsNum', 'perpage', 'id', 'question', 'countQuestion'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }

    public function displayQuestionnaire(Request $request)
    {
        $perpage = 10;
        $skills = [];
        $skillsNum = 0;
        $menu = 'questionnaires-display';
        return view('admin.questionnaire.display', compact('skills', 'menu',  'skillsNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }
}