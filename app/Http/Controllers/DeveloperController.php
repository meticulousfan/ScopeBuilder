<?php

namespace App\Http\Controllers;

use App\Models\PayoutRequest;
use App\Models\Project;
use App\Models\User;
use App\Models\ClientProject;
use App\Models\UserVerificationToken;
use App\Models\PdfPermission;
use App\Models\BBBMeeting;
use App\Models\Settings;
use App\Models\Skills;
use App\Models\Earnings;
use App\Models\PayoutMethods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Laravel\Cashier\PaymentMethod;
use Mail;

class DeveloperController extends Controller
{

    // Show Login Form
    public function showLogin(Request $request)
    {
        $waID = $request->get('waID');
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.clients');
            } elseif ($user->hasRole('client')) {
                return redirect()->route('client.projects');
            } elseif ($user->hasRole('freelancer')) {
                return redirect()->route('developer.dashboard');
            }
        }
        return view('auth.developer.login', compact('waID'));
    }

    // Show Register Form
    public function showRegister(Request $request)
    {
        $waID = $request->get('waID');
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.clients');
            } elseif ($user->hasRole('client')) {
                return redirect()->route('client.projects');
            } elseif ($user->hasRole('freelancer')) {
                return redirect()->route('developer.dashboard');
            }
        }
        $maxNumberSkillsDevCanChoose = 1;
        if (Settings::first() != NULL) {
            $maxNumberSkillsDevCanChoose = Settings::first()->maxNumberSkillsDevCanChoose;
        }
        $skills = Skills::where('status', 1)->get();
        return view('auth.developer.register', compact('waID','skills', 'maxNumberSkillsDevCanChoose'));
    }

    // Show Forgot Password Form
    public function showForgot()
    {
        return view('auth.developer.forgot');
    }

    // Show Reset Password Form
    public function showReset($token)
    {
        return view('auth.developer.reset', ['token' => $token]);
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

        return redirect()->route('developer.login.form')->with('success', 'Your password has been changed!');
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $get_email = $request->post('email');
        $user_data = User::where('email', $get_email)->first();
        $uid = $user_data->id;
        $current_role = '2';

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

            Mail::send('emails.forgotPasswordDeveloper', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return back()->with('success', 'We have e-mailed your password reset link!');
        } else {
            return back()->with('error', 'Email Not Found!');
        }
    }

    // Register a new Developer
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $token = Str::random(16);
        $user = User::create([
            'username'          => $request->username,
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'referral_token'    => $request->username,
            'skills'    => json_encode($request->skills)
        ]);
        $role = Role::where('name', 'freelancer')->first();
        if ($role) {
            $user->assignRole($role);
        } else {
            $role1 = Role::create(['name' => 'freelancer']);
            $user->assignRole($role1);
        }
        if ($request->waID && $request->waID != '') {
            $user = User::findOrFail($user->id);
            $user->wa_id = $request->waID;
            $user->update();
            if(!$user->wa_verified){
                $apiURL = env('WORKALERT_URL').'/api/save-scopebuilder';
                $ref_link = env('APP_URL').'?ref='.$user->referral_token;
                $headers = [
                    'Content-Type' => 'application/json',
                    'x-api-key' =>  env('WORKALERT_API_KEY'),
                ];
                $response = Http::withHeaders($headers)->get($apiURL, [
                    'ref' => $ref_link,
                    'waID' => $request->waID,
                ]);
                $result = $response->json();
                if(!$result['error']){                                
                    $user = User::findOrFail($user->id);
                    $user->wa_id = $request->waID;
                    $user->wa_verified = true;
                    $user->update();
                }
            }

        }
        $token = Str::random(64);

        UserVerificationToken::create([
            'user_id' => $user->id,
            'token' => $token
        ]);

        // Send Email to Verify Email
        $details = [
            'token' => $token,
            'role'  => 'freelancer',
            'email' => $user->email
        ];

        dispatch(new \App\Jobs\VerifyEmailJob($details));

        return redirect()->route('developer.login.form')->with('success', 'A fresh verification link has been sent to your email address.');
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
                return back()->with('error', 'You are not authorized to access this page!');
            }
            if ($user->hasRole('freelancer')) {
                if (!$user->is_email_verified) {
                    $token = UserVerificationToken::where('user_id', $user->id)->first();

                    // Send Email to Verify Email
                    $details = [
                        'token' => $token->token,
                        'role'  => 'freelancer',
                        'email' => $user->email
                    ];

                    dispatch(new \App\Jobs\VerifyEmailJob($details));
                    return back()->with('error', 'Your Email Address is not verified. Please check your Email for a fresh verification link.');
                }

                if (Auth::attempt($credentials)) {
                    if ($request->waID && $request->waID != '') {
                        $user = User::findOrFail($user->id);
                        $user->wa_id = $request->waID;
                        $user->update();
                        if(!$user->wa_verified){
                            $apiURL = env('WORKALERT_URL').'/api/save-scopebuilder';
                            $ref_link = env('APP_URL').'?ref='.$user->referral_token;
                            $headers = [
                                'Content-Type' => 'application/json',
                                'x-api-key' =>  env('WORKALERT_API_KEY'),
                            ];
                            $response = Http::withHeaders($headers)->get($apiURL, [
                                'ref' => $ref_link,
                                'waID' => $request->waID,
                            ]);
                            $result = $response->json();
                            if(!$result['error']){                                
                                $user = User::findOrFail($user->id);
                                $user->wa_id = $request->waID;
                                $user->wa_verified = true;
                                $user->update();
                            }
                        }

                    }
                    return redirect()->route('developer.dashboard');
                } else {
                    return back()->with('error', 'Credentials do not match!');
                }
            }

            if ($user->hasRole('client')) {
                return redirect()->route('client.login');
            }
        }

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Credentials do not match!');
        }

        return redirect()->route('developer.login.form');
    }
    public function verifyDev(Request $request)
    {
        $wa_id = $request->get('waID');
        $ref_id = $request->get('ref');
        $apiKey = $request->header('x-api-key');
        if (!isset($apiKey) || $apiKey != env('WORKALERT_API_KEY')) {

            return response()->json(
                [
                    'error' => true,
                    'status' => '2020',
                    'user' => NULL,
                    'message' => 'Invalid x-api-key'
                ]
            );
        }
        if (isset($wa_id) && isset($ref_id)) {

            $dev = User::select(
                'users.*',
                DB::raw('COALESCE((SELECT ROUND(SUM(dev_call_ratings.stars)/COUNT(dev_call_ratings.stars),2) FROM dev_call_ratings WHERE dev_call_ratings.dev_id = users.id),0) as rating')
            )->role('freelancer')->where('wa_id', $wa_id)->where('referral_token', $ref_id)->first();
            if ($dev) {
                $dev_skills = '';
                if (is_array(json_decode($dev->skills))) {
                    foreach (json_decode($dev->skills) as $one) {
                        $skill = Skills::where('id', $one)->first();
                        if ($dev_skills != '') $dev_skills .= ', ';
                        if ($skill) $dev_skills .= $skill->name;
                    }
                }
                $user = [
                    'id' => $dev->id,
                    'name' => $dev->name,
                    'fullname' => $dev->username,
                    'email' => $dev->email,
                    'skills' => $dev_skills,
                    'rating' => $dev->rating,
                ];
                return response()->json(
                    [
                        'error' => 'null',
                        'status' => '200',
                        'user' => $user,
                        'message' => 'success'
                    ]
                );
            }
        }

        return response()->json(
            [
                'error' => true,
                'status' => '2020',
                'user' => NULL,
                'message' => 'No User'
            ]
        );
    }

    public function myProjects()
    {
        if (Auth::check()) {
            $id =  Auth::id();
            $projects = ClientProject::select('client_projects.*', DB::raw('HEX(client_projects.uuid) As BLOBText'))
                ->join('collaborative_projects', 'client_projects.id', '=', 'collaborative_projects.project_id')
                ->join('users', 'collaborative_projects.assigned_user_email', '=', 'users.email')
                ->where('users.id', Auth::id())
                ->groupBy('client_projects.id')
                ->get();

            $project_ids = clone $projects;
            $project_ids = $project_ids->pluck('id')->toArray();
            $newProjects = collect();

            foreach($projects as $project) {
                $project->collaborative_project = 1;
                $newProjects->push($project);
            }

            $pdf_projects = PdfPermission::where('user_id', $id)->pluck('project_id')->toArray();
            $collaborative_and_pdf = [];
            foreach($pdf_projects as $pdf_project) {
                if(in_array($pdf_project, $project_ids)) {
                    $project = $projects->where('id', $pdf_project)->first();
                    $collaborative_and_pdf[] = $project->id;
                } else {
                    $project = ClientProject::select('client_projects.*', DB::raw('HEX(client_projects.uuid) As BLOBText'))->where('id', $pdf_project)->first();
                    $project->can_view_pdf = 1;
                    if($project) {
                        $newProjects->push($project);
                    }
                }
            }

            foreach($newProjects as $project) {
                if(in_array($project->id, $collaborative_and_pdf)) {
                    $project->can_view_pdf = 1;
                }
            }
            
            $projects = $newProjects;

            return view('developer.projects.index', compact('projects'));
        } else {
            return route('developer.login.form');
        }
    }

    public function mypayouts(Request $request)
    {
        if (Auth::check()) {
            
            $user = Auth::user();
            $uid = $user->id;

            $payoutmethods = PayoutMethods::where('status','=','1')->get();
            $minimumPayoutAmount = 0;
            $maximumPayoutAmount = 0;
            $minimumDaysBeforeRPayout = 0;
            if (Settings::first() != NULL) {
                $minimumPayoutAmount = Settings::first()->minimumPayoutAmount;
                $maximumPayoutAmount = Settings::first()->maximumPayoutAmount;
                $minimumDaysBeforeRPayout = Settings::first()->minimumDaysBeforeRPayout;
            }
            $date = new \DateTime();
            $date->modify("-".$minimumDaysBeforeRPayout." day");
            $time = $date->format('Y-m-d H:i:s');

            $perpage = 10;
            $payouts = PayoutRequest::select('payout_requests.*','payout_methods.name AS method_name')
            ->join('payout_methods','payout_methods.id','=','payout_requests.payment_method')
            ->orderBy('payout_requests.created_at', 'DESC')->where( 'payout_requests.user_id','=', $uid)->paginate($perpage)->onEachSide(2);

            $totalNum = PayoutRequest::select('payout_requests.*','payout_methods.name AS method_name')
            ->join('payout_methods','payout_methods.id','=','payout_requests.payment_method')
            ->orderBy('payout_requests.created_at', 'DESC')->where( 'payout_requests.user_id','=', $uid)->count();
            
            $earnings = Earnings::select(DB::raw('COALESCE( ROUND(SUM(earnings.amount),2)) AS totalearning'),DB::raw('COALESCE( ROUND(SUM(IF(created_at<="'.$time.'",earnings.amount,0)),2)) AS availableEarning'))->where(['status' => '1', 'dev_id' => $uid])->first();
            $totalEarning = $earnings->totalearning;
            $availableEarning = $earnings->availableEarning;

            $payoutRequest = PayoutRequest::select(DB::raw('COALESCE( ROUND(SUM(IF(is_paid=1,payout_requests.amount,0)),2)) AS payout'), DB::raw('COALESCE( ROUND(SUM(IF(is_paid=0,payout_requests.amount,0)),2)) AS pendingPayouts'))->where( 'user_id','=', $uid)->first();
            $totalPayouts = $payoutRequest->payout;
            $pendingPayouts = $payoutRequest->pendingPayouts;            
            $totalBalance =   $totalEarning - $totalPayouts - $pendingPayouts;
            $availableBalance =  $availableEarning - $totalPayouts - $pendingPayouts;
            return view('developer.payouts.index', compact('totalPayouts','pendingPayouts','totalBalance','availableBalance','minimumPayoutAmount','maximumPayoutAmount','payoutmethods','payouts','totalNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);

        } else {
            return route('developer.login.form');
        }
    }
    // Request Payout
    public function requestPayouts(Request $request)
    {
        $user = Auth::user();
        $uid = $user->id;
        $minimumPayoutAmount = 0;
        $maximumPayoutAmount = 0;
        $minimumDaysBeforeRPayout = 0;
        if (Settings::first() != NULL) {
            $minimumPayoutAmount = Settings::first()->minimumPayoutAmount;
            $maximumPayoutAmount = Settings::first()->maximumPayoutAmount;
            $minimumDaysBeforeRPayout = Settings::first()->minimumDaysBeforeRPayout;
        }
        $date = new \DateTime();
        $date->modify("-".$minimumDaysBeforeRPayout." day");
        $time = $date->format('Y-m-d H:i:s');
        $totalEarning = Earnings::select(DB::raw('COALESCE( ROUND(SUM(earnings.amount),2)) AS totalearning'))->where(['status' => '1', 'dev_id' => $uid])->where('created_at','<=',$time)->first()->totalearning;
        
        $payoutRequest = PayoutRequest::select(DB::raw('COALESCE( ROUND(SUM(IF(is_paid=1,payout_requests.amount,0)),2)) AS payout'), DB::raw('COALESCE( ROUND(SUM(IF(is_paid=0,payout_requests.amount,0)),2)) AS pendingPayouts'))->where( 'user_id','=', $uid)->first();
        $totalPayouts = $payoutRequest->payout;
        $pendingPayouts = $payoutRequest->pendingPayouts;  
        $availableBalance =  $totalEarning - $totalPayouts - $pendingPayouts;

        if($request->amount > $availableBalance || $request->amount < $minimumPayoutAmount || $request->amount > $maximumPayoutAmount )
        return back()->with('error', 'Amount error!');
        $payout = new PayoutRequest();
        $payout->amount = $request->amount;
        $payout->user_id = Auth::id();
        $payout->payment_method = $request->payment_method;
        $payout->save();  
        $username = Auth::user()->name;  
        $method = 'PayPal';
        $payoutMethods = PayoutMethods::where('id',$request->method_id)->first();
        if($payoutMethods) $method = $payoutMethods->name;
        
        $details = [
            'is_paid' => '2',
            'url' => route('admin.payouts'),
            'text' => $username.' has requested a Payout of $'.number_format(round(floatval($request->amount), 2), 2).' via '.$payoutMethods,
            'notes' => ''
        ];
        $users = User::role('admin')->get();
        foreach($users AS $user){
            \Mail::to($user->email)->send(new \App\Mail\PayoutEmail($details));
        }

        return back()->with('success', 'Payout Requested Successfully!');
    }

    public function myProfile()
    {
        if (Auth::check()) {
            $user = User::find(auth()->user()->id);
            $skills = Skills::where('status', 1)->get();
            $maxNumberSkillsDevCanChoose = 1;
            $skillSubmission = false;
            if (Settings::first() != NULL) {
                $maxNumberSkillsDevCanChoose = Settings::first()->maxNumberSkillsDevCanChoose;
                $skillSubmission = Settings::first()->skillSubmission;
            }
            return view('developer.profile.index', compact('user', 'skills', 'maxNumberSkillsDevCanChoose', 'skillSubmission'));
        } else {
            return route('developer.login.form');
        }
    }

    public function showSecuritySettings()
    {
        if (Auth::check()) {
            return view('developer.profile.security-settings');
        } else {
            return route('developer.login.form');
        }
    }

    public function showPaymentSettings()
    {
        if (Auth::check()) {
            return view('developer.profile.payment-settings');
        } else {
            return route('developer.login.form');
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
        $user->country_id = $request->country_id;
        $user->timezone = $request->timezone;
        $user->company = $request->company;
        $user->language = $request->language;
        $user->skills = json_encode($request->skills);
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

    // Invite Client
    public function inviteClient(Request $request)
    {
        if ($request->id) {
            PdfPermission::where('project_id', $request->id)->delete();
            Project::where('id', $request->id)->update(['is_shared' => 0]);
            return response()->json(true);
        }
        if ($request->mockup == 1) {
            PdfPermission::where('project_id', $request->project_id)->delete();
            Project::where('id', $request->project_id)->update(['is_shared' => 0]);
        }
        if ($request->mockup == 0) {
            $user_email = $request->user_email;
            foreach ($user_email as $user) {
                $permission = new PdfPermission();
                $permission->project_id   = $request->project_id;
                $permission->user_id  = $user;
                $permission->shared_by  = Auth::id();
                $permission->save();
            }
            Project::where('id', $request->project_id)->update(['is_shared' => 1]);
            if ($request->user_email) {
                // Send Email to invite Client
                foreach ($user_email as $mail) {
                    $user = User::where('id', $mail)->first();
                    $project_name = Project::where('id', $request->project_id)->first();
                    $details = [
                        'project' => $project_name->name,
                        'user'    => Auth::user()->name,
                        'link'    => $request->pdf_link,
                    ];
                    \Mail::to($user->email)->send(new \App\Mail\NotifyfCustomer($details));
                }
            }
        }
        return back()->with('success', 'Email Sent Successfully!');
    }

    // Logout
    public function logout()
    {
        Session::flush();

        Auth::logout();

        return redirect()->route('developer.login.form');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function verifyAccount($token)
    {
        $verifyUser = UserVerificationToken::where('token', $token)->first();

        $message = 'Sorry your email cannot be identified.';

        if (!is_null($verifyUser)) {
            $user = $verifyUser->user;

            if (!$user->is_email_verified) {
                $verifyUser->user->is_email_verified = 1;
                $verifyUser->user->save();
                $message = "Your e-mail is verified. You can now login.";
            } else {
                $message = "Your e-mail is already verified. You can now login.";
            }
            return redirect()->route('developer.login.form')->with('success', $message);
        }
        return redirect()->route('developer.login.form')->with('error', $message);
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $uid = $user->id;

        $perpage = 10;
        $histories = Earnings::select('earnings.*','client_projects.name AS project_name')
        ->join('transactions', 'transactions.id', '=', 'earnings.transaction_id', 'left')
        ->join('client_projects', 'client_projects.uuid', '=', 'transactions.project_id', 'left')
        ->orderBy('earnings.id', 'DESC')->where(['earnings.status' => '1', 'earnings.dev_id' => $uid])->paginate($perpage)->onEachSide(2);
        $totalNum = Earnings::where(['status' => '1', 'dev_id' => $uid])->count();
        $totalEarning = Earnings::select(DB::raw('COALESCE( ROUND(SUM(earnings.amount),2)) AS totalearning'))->where(['status' => '1', 'dev_id' => $uid])->first()->totalearning;
        $totalPayout = PayoutRequest::select(DB::raw('COALESCE( ROUND(SUM(payout_requests.amount),2)) AS totalpayout'))->where( 'user_id','=', $uid)->where('is_paid','!=','2')->first()->totalpayout;
        $currentBalance =  $totalEarning - $totalPayout;
        return view('developer.dashboard', compact('totalEarning','currentBalance','histories','totalNum', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
    }

    public function project_list(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $uid = $user->id;
            #$projects = ClientProject::orderBy('id', 'DESC')->where(['contact_developer'=>'1', 'assigned_to_user_id'=>$uid])->get();
            #return view('developer.project_list', compact('projects'));

            $perpage = 10;
            $projects = ClientProject::orderBy('id', 'DESC')->where(['contact_developer' => '1', 'assigned_to_user_id' => $uid])->paginate($perpage)->onEachSide(2);
            return view('developer.project_list', compact('projects', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);

            #$perpage = 10;
            #$perpage = $request->perpage ?? 10;
            #$projects = ClientProject::latest()->paginate($perpage)->onEachSide(2);
            #return view('admin.projects', compact('projects', 'perpage'))->with('i', ($request->input('page', 1) - 1) * $perpage);
        } else {
            return route('developer.login.form');
        }
    }

    public function callRoom($project_uuid)
    {
        if (Auth::check() && isset($project_uuid)) {
            $pricePerCallMinute = 1;
            if (Settings::first() != NULL) {
                $pricePerCallMinute = Settings::first()->pricePerCallMinute;
            }

            $projects = ClientProject::whereUuid($project_uuid, 'uuid')->get();
            if (count($projects) == 0) {
                return redirect()->route('developer.projects');
            }
            $project = $projects[0];
            $meetingRooms = BBBMeeting::where('project_id', $project->id)->latest()->get();
            if (count($meetingRooms) == 0) {
                return redirect()->route('client.projects');
            }
            $meetingRoom = $meetingRooms[0];
            // Create BBB Meeting
            return view('developer.projects.call-room', compact(['project', 'meetingRoom', 'pricePerCallMinute']));
        } else {
            return route('developer.login.form');
        }
    }
}
